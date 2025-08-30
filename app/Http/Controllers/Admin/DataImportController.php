<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120', // Increased to 5MB for Excel files
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        $imported = 0;
        $errors = [];
        $data = [];

        try {
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Handle Excel files
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

            // Clean header names (remove whitespace, make lowercase)
            $header = array_map(function($col) {
                return trim(strtolower($col));
            }, $header);

            // Process each row
            foreach ($data as $rowIndex => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                $studentData = array_combine($header, $row);
                
                // Validate required fields
                if (empty($studentData['phone_number']) || empty($studentData['first_name']) || empty($studentData['last_name'])) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": Missing required fields (phone_number, first_name, last_name)";
                    continue;
                }

                try {
                    // Check if user already exists
                    $existingUser = User::where('phone_number', $studentData['phone_number'])->first();
                    if ($existingUser) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Phone number {$studentData['phone_number']} already exists";
                        continue;
                    }

                    User::create([
                        'phone_number' => trim($studentData['phone_number']),
                        'first_name' => trim($studentData['first_name']),
                        'last_name' => trim($studentData['last_name']),
                        'email' => !empty($studentData['email']) ? trim($studentData['email']) : null,
                        'school' => !empty($studentData['school']) ? trim($studentData['school']) : null,
                        'matriculation_number' => !empty($studentData['matriculation_number']) ? trim($studentData['matriculation_number']) : null,
                        'address' => !empty($studentData['address']) ? trim($studentData['address']) : null,
                        'password' => Hash::make('password123'), // Default password
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }

        } catch (\Exception $e) {
            return back()->with([
                'error' => "Failed to process file: " . $e->getMessage()
            ]);
        }

        $message = "Successfully imported {$imported} students.";
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " rows had errors.";
        }

        return back()->with([
            'success' => $message,
            'import_errors' => $errors // Changed from 'errors' to avoid conflict with Laravel's validation errors
        ]);
    }
}