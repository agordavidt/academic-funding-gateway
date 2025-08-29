<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use League\Csv\Reader;

class CsvImportService
{
    public function importStudents(UploadedFile $file): array
    {
        $csv = Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        
        $records = $csv->getRecords();
        $success = 0;
        $failed = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($records as $offset => $record) {
                try {
                    // Validate required fields
                    if (empty($record['phone_number']) || empty($record['first_name']) || empty($record['last_name'])) {
                        $failed++;
                        $errors[] = "Row {$offset}: Missing required fields";
                        continue;
                    }
                    
                    // Clean phone number
                    $phoneNumber = preg_replace('/[^0-9+]/', '', $record['phone_number']);
                    
                    // Check if user already exists
                    if (User::where('phone_number', $phoneNumber)->exists()) {
                        $failed++;
                        $errors[] = "Row {$offset}: Phone number already exists";
                        continue;
                    }
                    
                    User::create([
                        'phone_number' => $phoneNumber,
                        'email' => $record['email'] ?? null,
                        'first_name' => $record['first_name'],
                        'last_name' => $record['last_name'],
                        'school' => $record['school'] ?? null,
                        'matriculation_number' => $record['matriculation_number'] ?? null,
                        'state_of_origin' => $record['state_of_origin'] ?? null,
                        'lga' => $record['lga'] ?? null,
                        'password' => Hash::make($phoneNumber), // Default password is phone number
                        'is_admin' => false
                    ]);
                    
                    $success++;
                    
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Row {$offset}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return [
                'success' => $success,
                'failed' => $failed,
                'errors' => $errors
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}