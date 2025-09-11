<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImportController extends Controller
{
    /**
     * Show the form for importing or creating student data.
     * * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Handle the manual creation of a single student record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|unique:users,phone_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'school' => 'nullable|string|max:255',
            'matriculation_number' => 'nullable|string|max:50',
        ]);

        try {
            User::create([
                'phone_number' => $request->phone_number,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'school' => $request->school,
                'matriculation_number' => $request->matriculation_number,
                'password' => Hash::make('password123'), // Default password for new users
            ]);

            return back()->with('success', 'Student record created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create student record: ' . $e->getMessage());
        }
    }

    /**
     * Handle the file upload and import process for student data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
            if (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                $rows = array_filter($rows, function($row) {
                    return !empty(array_filter($row));
                });
                
                $header = array_shift($rows);
                $data = $rows;
            } else {
                $fileContent = file($file->getRealPath());
                $data = array_map('str_getcsv', $fileContent);
                $header = array_shift($data);
            }

            $header = array_map(function($col) {
                return trim(strtolower($col));
            }, $header);

            foreach ($data as $rowIndex => $row) {
                if (empty(array_filter($row))) {
                    continue;
                }

                $studentData = array_combine($header, $row);
                
                if (empty($studentData['phone_number']) || empty($studentData['first_name']) || empty($studentData['last_name'])) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": Missing required fields (phone_number, first_name, last_name)";
                    continue;
                }

                try {
                    // Use the cleanPhoneNumber mutator before attempting to find existing user
                    $cleanPhoneNumber = User::cleanPhoneNumberStatic($studentData['phone_number']);
                    $existingUser = User::where('phone_number', $cleanPhoneNumber)->first();
                    if ($existingUser) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Phone number {$cleanPhoneNumber} already exists";
                        continue;
                    }
                    
                    User::create([
                        'phone_number' => $cleanPhoneNumber, // Use the cleaned number for creation
                        'first_name' => trim($studentData['first_name']),
                        'last_name' => trim($studentData['last_name']),
                        'email' => !empty($studentData['email']) ? trim($studentData['email']) : null,
                        'school' => !empty($studentData['school']) ? trim($studentData['school']) : null,
                        'matriculation_number' => !empty($studentData['matriculation_number']) ? trim($studentData['matriculation_number']) : null,
                        'password' => Hash::make('password123'),
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
            'import_errors' => $errors
        ]);
    }
}