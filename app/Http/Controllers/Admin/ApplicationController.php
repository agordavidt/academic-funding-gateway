<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware(['auth', 'admin']);
        $this->notificationService = $notificationService;
    }
    
    public function index(Request $request)
    {
        $query = Application::with(['user', 'trainingInstitution']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('application_status', $request->status);
            });
        }
        
        if ($request->filled('institution')) {
            $query->where('training_institution_id', $request->institution);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        $applications = $query->latest()->paginate(20);
        
        return view('admin.applications.index', compact('applications'));
    }
    
    public function show(Application $application)
    {
        $application->load(['user', 'trainingInstitution', 'reviewer']);
        
        return view('admin.applications.show', compact('application'));
    }
    
    public function review(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:reviewing,accepted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
            'approved_amount' => 'nullable|numeric|min:0|max:' . config('funding.max_grant_amount'),
            'rejection_reason' => 'required_if:status,rejected|string|max:1000'
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $application->user->application_status;
            $newStatus = $request->status;
            
            // Update application
            $updateData = [
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id()
            ];
            
            if ($newStatus === 'accepted' && $request->filled('approved_amount')) {
                $updateData['approved_amount'] = $request->approved_amount;
            }
            
            if ($newStatus === 'rejected') {
                $updateData['rejection_reason'] = $request->rejection_reason;
            }
            
            $application->update($updateData);
            
            // Update user status
            $application->user->update([
                'application_status' => $newStatus
            ]);
            
            DB::commit();
            
            // Send notifications (with error handling)
            if ($oldStatus !== $newStatus) {
                try {
                    $message = $newStatus === 'rejected' ? $request->rejection_reason : $request->admin_notes;
                    $this->notificationService->sendApplicationStatusUpdate(
                        $application->user,
                        $newStatus,
                        $message
                    );
                } catch (\Exception $e) {
                    // Log but don't break the flow
                    \Log::error('Application review notification failed', [
                        'application_id' => $application->id,
                        'user_id' => $application->user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            return redirect()->back()
                ->with('success', 'Application reviewed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to review application. Please try again.');
        }
    }
}