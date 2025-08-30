<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DataImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data);

        $imported = 0;
        $errors = [];

        foreach ($data as $row) {
            $studentData = array_combine($header, $row);
            
            try {
                User::create([
                    'phone_number' => $studentData['phone_number'],
                    'first_name' => $studentData['first_name'],
                    'last_name' => $studentData['last_name'],
                    'email' => $studentData['email'] ?? null,
                    'school' => $studentData['school'] ?? null,
                    'matriculation_number' => $studentData['matriculation_number'] ?? null,
                    'address' => $studentData['address'] ?? null,
                    'password' => Hash::make('password123'), // Default password
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row error: " . implode(',', $row) . " - " . $e->getMessage();
            }
        }

        return back()->with([
            'success' => "Successfully imported {$imported} students.",
            'errors' => $errors
        ]);
    }
}