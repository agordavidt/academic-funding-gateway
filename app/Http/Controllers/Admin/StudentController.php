<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CsvImportService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    protected $csvImportService;
    protected $notificationService;
    
    public function __construct(CsvImportService $csvImportService, NotificationService $notificationService)
    {
        $this->middleware(['auth', 'admin']);
        $this->csvImportService = $csvImportService;
        $this->notificationService = $notificationService;
    }
    
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->with(['application.trainingInstitution', 'latestPayment']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }
        
        $students = $query->latest()->paginate(20);
        
        return view('admin.students.index', compact('students'));
    }
    
    public function show(User $student)
    {
        if ($student->is_admin) {
            abort(404);
        }
        
        $student->load(['application.trainingInstitution', 'payments', 'notifications']);
        
        return view('admin.students.show', compact('student'));
    }
    
    public function updateStatus(Request $request, User $student)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewing,accepted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'approved_amount' => 'nullable|numeric|min:0|max:' . config('funding.max_grant_amount')
        ]);
        
        if ($student->is_admin) {
            abort(404);
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $student->application_status;
            $newStatus = $request->status;
            
            // Update user status
            $student->update([
                'application_status' => $newStatus
            ]);
            
            // Update application if exists
            if ($student->application) {
                $updateData = [
                    'admin_notes' => $request->admin_notes,
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id()
                ];
                
                if ($newStatus === 'accepted' && $request->filled('approved_amount')) {
                    $updateData['approved_amount'] = $request->approved_amount;
                }
                
                if ($newStatus === 'rejected' && $request->filled('admin_notes')) {
                    $updateData['rejection_reason'] = $request->admin_notes;
                }
                
                $student->application->update($updateData);
            }
            
            DB::commit();
            
            // Send notifications (with error handling)
            if ($oldStatus !== $newStatus) {
                try {
                    $this->notificationService->sendApplicationStatusUpdate(
                        $student, 
                        $newStatus, 
                        $request->admin_notes
                    );
                } catch (\Exception $e) {
                    // Log but don't break the flow
                    \Log::error('Status update notification failed', [
                        'user_id' => $student->id,
                        'status' => $newStatus,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            return redirect()->back()
                ->with('success', 'Student status updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }
    
    public function import()
    {
        return view('admin.students.import');
    }
    
    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120' // 5MB max
        ]);
        
        try {
            $file = $request->file('csv_file');
            $result = $this->csvImportService->importStudents($file);
            
            return redirect()->route('admin.students.index')
                ->with('success', "Successfully imported {$result['success']} students. {$result['failed']} failed.");
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}