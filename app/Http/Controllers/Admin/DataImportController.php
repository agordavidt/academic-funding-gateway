<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImportController extends Controller
{
    /**
     * Show the form for importing or creating student data.
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Handle the manual creation of a single student record.
     */
    public function create(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|unique:users,phone_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'school' => 'nullable|string|max:255',
        ]);

        try {
            User::create([
                'phone_number' => $request->phone_number, // Will be cleaned by mutator
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'school' => $request->school,
                'password' => Hash::make('password123'), // Default password
                'registration_stage' => 'imported',
                'payment_status' => 'pending',
                'application_status' => 'pending',
            ]);

            return back()->with('success', 'Student record created successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to create student record: ' . $e->getMessage());
            return back()->with('error', 'Failed to create student record: ' . $e->getMessage());
        }
    }

    /**
     * Handle the file upload and import process for student data.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        $imported = 0;
        $errors = [];
        $data = [];

        try {
            // Handle Excel files
            if (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Remove empty rows
                $rows = array_filter($rows, function($row) {
                    return !empty(array_filter($row));
                });
                
                $header = array_shift($rows);
                $data = $rows;
            } else {
                // Handle CSV files
                $fileContent = file($file->getRealPath());
                $data = array_map('str_getcsv', $fileContent);
                $header = array_shift($data);
            }

            // Clean header names
            $header = array_map(function($col) {
                return trim(strtolower(str_replace(' ', '_', $col)));
            }, $header);

            // Process each row
            foreach ($data as $rowIndex => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                $studentData = array_combine($header, $row);
                
                // Check required fields
                if (empty(trim($studentData['phone_number'] ?? '')) || 
                    empty(trim($studentData['first_name'] ?? '')) || 
                    empty(trim($studentData['last_name'] ?? ''))) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": Missing required fields (phone_number, first_name, last_name)";
                    continue;
                }

                try {
                    // Clean phone number using the model method
                    $cleanPhoneNumber = User::cleanPhoneNumberStatic($studentData['phone_number']);
                    
                    // Check if user already exists
                    $existingUser = User::where('phone_number', $cleanPhoneNumber)->first();
                    if ($existingUser) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Phone number {$cleanPhoneNumber} already exists";
                        continue;
                    }

                    // Check email uniqueness if provided
                    $email = !empty(trim($studentData['email'] ?? '')) ? trim($studentData['email']) : null;
                    if ($email && User::where('email', $email)->exists()) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Email {$email} already exists";
                        continue;
                    }
                    
                    // Create user record
                    User::create([
                        'phone_number' => $cleanPhoneNumber,
                        'first_name' => trim($studentData['first_name']),
                        'last_name' => trim($studentData['last_name']),
                        'email' => $email,
                        'school' => !empty(trim($studentData['school'] ?? '')) ? trim($studentData['school']) : null,
                        'password' => Hash::make('password123'),
                        'registration_stage' => 'imported',
                        'payment_status' => 'pending',
                        'application_status' => 'pending',
                    ]);
                    
                    $imported++;
                } catch (\Exception $e) {
                    Log::error('Import error for row ' . ($rowIndex + 2) . ': ' . $e->getMessage());
                    $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }
        } catch (\Exception $e) {
            Log::error('File processing error: ' . $e->getMessage());
            return back()->with('error', "Failed to process file: " . $e->getMessage());
        }

        $message = "Successfully imported {$imported} students.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " rows had errors.";
        }

        return back()->with([
            'success' => $message,
            'import_errors' => $errors
        ]);
    }

    /**
     * Download a sample CSV template for imports
     */
    public function downloadTemplate()
    {
        $filename = 'student_import_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'phone_number',
            'first_name', 
            'last_name',
            'email',
            'school'
        ];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($file, $columns);
            
            // Add sample data row
            fputcsv($file, [
                '08012345678',
                'John',
                'Doe', 
                'john.doe@example.com',
                'University of Lagos'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}