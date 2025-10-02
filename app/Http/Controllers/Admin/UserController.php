<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth; 
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $notificationService;
    protected $smsService;

    public function __construct(NotificationService $notificationService, SmsService $smsService)
    {
        $this->notificationService = $notificationService;
        $this->smsService = $smsService;
    }

    public function index(Request $request)
    {
        $query = User::with(['payments']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Application status filter
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        // School filter
        if ($request->filled('school')) {
            $query->where('school', 'like', "%{$request->school}%");
        }

        // Registration stage filter
        if ($request->filled('registration_stage')) {
            $query->where('registration_stage', $request->registration_stage);
        }

        // Created at filters
        if ($request->filled('created_from') || $request->filled('created_to')) {
            $query->createdBetween($request->created_from, $request->created_to);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get unique schools for filter dropdown
        $schools = User::whereNotNull('school')
                        ->where('school', '!=', '')
                        ->distinct()
                        ->pluck('school')
                        ->sort()
                        ->values();

        return view('admin.users.index', compact('users', 'schools'));
    }



    public function show(User $user)
    {
        $user->load(['payments', 'notifications']);
        
        $smsBalance = ['balance' => 'N/A', 'currency' => 'Credits']; // Placeholder
        
        return view('admin.users.show', compact('user', 'smsBalance'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'school' => 'nullable|string|max:255',
            'registration_stage' => 'required|in:imported,profile_completion,payment,completed',
            'payment_status' => 'required|in:pending,paid',
            'application_status' => 'required|in:pending,reviewing,accepted,rejected',
        ]);

        try {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number, // Will be cleaned by mutator
                'school' => $request->school,
                'registration_stage' => $request->registration_stage,
                'payment_status' => $request->payment_status,
                'application_status' => $request->application_status,
            ]);

            return redirect()->route('admin.users.show', $user)
                           ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            // Delete related payments first
            $user->payments()->delete();
            
            // Delete the user
            $user->delete();

            return redirect()->route('admin.users.index')
                           ->with('success', 'User and related records deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage(), ['user_id' => $user->id]);
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function updateApplicationStatus(Request $request, User $user)
    {
        $request->validate([
            'application_status' => 'required|in:pending,reviewing,accepted,rejected'
        ]);

        $oldStatus = $user->application_status;
        $user->update(['application_status' => $request->application_status]);

        if ($oldStatus !== $request->application_status) {
            try {
                $this->notificationService->sendApplicationStatusUpdate($user, $request->application_status);
                return back()->with('success', 'Application status updated successfully. Notifications sent to user.');
            } catch (Exception $e) {
                Log::error('Failed to send application status update notification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'status' => $request->application_status
                ]);
                return back()->with('warning', 'Application status updated, but notification failed. Error: ' . $e->getMessage());
            }
        }
        
        return back()->with('success', 'Application status updated successfully.');
    }

 

    public function approvePayment(Request $request, User $user, Payment $payment)
{
    // Safety check to ensure an admin is authenticated
    if (!Auth::guard('admin')->check()) {
        return back()->with('error', 'Authentication error. Please log in again.');
    }

    // Check if the payment belongs to the user
    if ($payment->user_id !== $user->id) {
        return back()->with('error', 'Payment does not belong to this user.');
    }

    // Check if the payment is in the 'submitted' status
    if ($payment->status !== 'submitted') {
        return back()->with('error', 'Payment cannot be approved as its status is not "submitted".');
    }

    DB::beginTransaction();
    try {
        $approvalNote = $request->input('approval_note');
        
        // Get the authenticated user
        $adminUser = Auth::guard('admin')->user();
        
        // Update the payment record
        $payment->status = 'success';
        $payment->gateway_response = [
            'approved_by' => $adminUser->full_name,
            'approved_at' => now(),
            'approval_note' => $approvalNote,
        ];
        $payment->save();

        // Update the user's payment status to 'paid'
        $user->payment_status = 'paid';
        $user->save();

        DB::commit();

        return back()->with('success', 'Payment approved successfully and user status updated to "paid".');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to approve payment: ' . $e->getMessage());
    }
}

  public function rejectPayment(Request $request, User $user, Payment $payment)
{
    // Safety check to ensure an admin is authenticated
    if (!Auth::guard('admin')->check()) {
        return back()->with('error', 'Authentication error. Please log in again.');
    }

    $request->validate([
        'rejection_reason' => 'required|string|max:255',
    ]);

    // Check if the payment belongs to the user
    if ($payment->user_id !== $user->id) {
        return back()->with('error', 'Payment does not belong to this user.');
    }
    
    // Check if the payment is in the 'submitted' status
    if ($payment->status !== 'submitted') {
        return back()->with('error', 'Payment cannot be rejected as its status is not "submitted".');
    }

    DB::beginTransaction();
    try {
        $adminUser = Auth::guard('admin')->user();

        // Update the payment record
        $payment->status = 'rejected';
        $payment->gateway_response = [
            'rejected_by' => $adminUser->full_name,
            'rejected_at' => now(),
            'rejection_reason' => $request->input('rejection_reason'),
        ];
        $payment->save();

        DB::commit();
        return back()->with('success', 'Payment rejected successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to reject payment: ' . $e->getMessage());
    }
}

  public function sendSms(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:160'
        ]);

        if (!$user->hasValidPhoneNumber()) {
            return back()->with('error', 'User does not have a valid phone number.');
        }

        // The try...catch block is simplified since the NotificationService will handle internal exceptions.
        $result = $this->notificationService->sendCustomSms($user, $request->message);

        if ($result['success']) {
            return back()->with('success', 'SMS sent successfully to ' . $user->phone_number);
        } else {
            return back()->with('error', 'Failed to send SMS: ' . $result['message']);
        }
    }


    public function bulkSms(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160',
            'recipients' => 'required|in:all,paid,pending,accepted,rejected,with_phone',
            'school_filter' => 'nullable|string',
            'created_since' => 'nullable|date',  // New validation for date filter
        ]);

        $query = User::query();

        // Apply recipient filters (ensure this logic is complete and correct in your file)
        switch ($request->recipients) {
            case 'paid':
                $query->where('payment_status', 'paid');
                break;
            case 'pending':
                $query->where('payment_status', 'pending');
                break;
            case 'accepted':
                $query->where('application_status', 'accepted');
                break;
            case 'rejected':
                $query->where('application_status', 'rejected');
                break;
            case 'with_phone':
                // Use the scope to filter down to users with a phone number set
                $query->withValidPhone(); 
                break;
            case 'all':
            default:
                // For 'all' we still need to filter for valid numbers to prevent early errors
                $query->withValidPhone();
                break;
        }

        if ($request->filled('school_filter')) {
            $query->where('school', 'like', "%{$request->school_filter}%");
        }

        // Apply created_since filter (new)
        if ($request->filled('created_since')) {
            $query->createdAfter($request->created_since);
        }

        // Retrieve the users. We need the models so the Notification Service can call notify() on them.
        // NOTE: The model-based filtering for hasValidPhoneNumber() is now done implicitly by
        // the NotificationService loop, but to prevent querying massive datasets, 
        // it's better to use the scope below if you didn't in the switch.
        
        // Ensure all users fetched have at least a non-empty phone number field.
        if ($request->recipients !== 'with_phone' && $request->recipients !== 'all') {
            // Apply the generic phone filter if not already applied
            $query->withValidPhone();
        }
        
        // Retrieve users as Eloquent Collections/Models
        $users = $query->get();

        // Secondary Model-based Validation (if your scope isn't strict enough)
        // Filter to users with *valid* phone numbers for SMS (using the model method)
        // This is optional if your withValidPhone scope is strict, but ensures cleaner data
        $users = $users->filter(fn ($user) => $user->hasValidPhoneNumber());


        $totalAttempted = $users->count();
        
        if ($totalAttempted === 0) {
            return back()->with('error', 'No users match the selected criteria or have valid phone numbers.');
        }

        // Call the service which now handles individual sending and counting
        // Pass the collection of valid User models
        $result = $this->notificationService->sendBulkSms($users->all(), $request->message); 
        // Result structure: ['success', 'message', 'sent_count', 'failed_count', 'total']

        $sent = $result['sent_count'] ?? 0;
        $failed = $result['failed_count'] ?? 0;

        if ($sent > 0 && $failed === 0) {
            $message = "✅ Bulk SMS sent successfully to **{$sent}** users.";
            $type = 'success';
        } elseif ($sent > 0 && $failed > 0) {
            $message = "⚠️ Bulk SMS completed with partial success. **Sent: {$sent}**. **Failed: {$failed}**.";
            $type = 'warning';
        } else {
            $message = "❌ Bulk SMS failed completely. **{$failed}** failures out of {$totalAttempted} valid recipients. Check logs for details.";
            $type = 'error';
        }

        return back()->with($type, $message);
    }
    public function smsSettings()
    {
        $balance = ['balance' => 'N/A', 'currency' => 'Credits'];
        $recentSms = \App\Models\Notification::where('type', 'LIKE', '%sms%')
                                            ->with('user')
                                            ->latest()
                                            ->take(20)
                                            ->get();

        return view('admin.sms.settings', compact('balance', 'recentSms'));
    }

    public function testSms(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:160'
        ]);

        try {
            $result = $this->smsService->sendSms($request->phone_number, $request->message);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send test SMS: ' . $e->getMessage(), [
                'phone' => $request->phone_number
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test SMS.'
            ], 500);
        }
    }
}