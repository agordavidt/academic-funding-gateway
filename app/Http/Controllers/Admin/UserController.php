<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\SmsService;
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

    public function approvePayment(Request $request, User $user)
    {
        $payment = $user->payments()->where('status', 'submitted')->first();
        
        if (!$payment) {
            return back()->with('error', 'No pending payment found for this user.');
        }

        $payment->update([
            'status' => 'success',
            'gateway_response' => json_encode(array_merge(
                json_decode($payment->gateway_response ?? '{}', true), 
                [
                    'approved_by' => auth('admin')->user()->name ?? 'Admin',
                    'approved_at' => now()->toISOString(),
                    'approval_note' => $request->approval_note
                ]
            ))
        ]);

        $user->update([
            'payment_status' => 'paid'
        ]);

        try {
            $this->notificationService->sendPaymentApproved($user, $payment);
            return back()->with('success', 'Payment approved successfully. User has been notified.');
        } catch (Exception $e) {
            Log::error('Failed to send payment approval notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'payment_id' => $payment->id
            ]);
            return back()->with('warning', 'Payment approved, but notification failed. Error: ' . $e->getMessage());
        }
    }

    public function rejectPayment(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $payment = $user->payments()->where('status', 'submitted')->first();
        
        if (!$payment) {
            return back()->with('error', 'No pending payment found for this user.');
        }

        $payment->update([
            'status' => 'rejected',
            'gateway_response' => json_encode(array_merge(
                json_decode($payment->gateway_response ?? '{}', true), 
                [
                    'rejected_by' => auth('admin')->user()->name ?? 'Admin',
                    'rejected_at' => now()->toISOString(),
                    'rejection_reason' => $request->rejection_reason
                ]
            ))
        ]);

        try {
            $this->notificationService->sendPaymentRejected($user, $payment, $request->rejection_reason);
            return back()->with('success', 'Payment rejected. User has been notified.');
        } catch (Exception $e) {
            Log::error('Failed to send payment rejection notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'payment_id' => $payment->id
            ]);
            return back()->with('warning', 'Payment rejected, but notification failed. Error: ' . $e->getMessage());
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

        try {
            $result = $this->notificationService->sendCustomSms($user, $request->message);

            if ($result['success']) {
                return back()->with('success', 'SMS sent successfully to ' . $user->phone_number);
            } else {
                return back()->with('error', 'Failed to send SMS: ' . $result['message']);
            }
        } catch (Exception $e) {
            Log::error('Failed to send custom SMS: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'phone' => $user->phone_number
            ]);
            return back()->with('error', 'Failed to send SMS. Please check the logs.');
        }
    }

    public function bulkSms(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160',
            'recipients' => 'required|in:all,paid,pending,accepted,rejected,with_phone',
            'school_filter' => 'nullable|string'
        ]);

        $query = User::query();

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
                $query->whereNotNull('phone_number')->where('phone_number', '!=', '');
                break;
            case 'all':
                break;
        }

        if ($request->filled('school_filter')) {
            $query->where('school', 'like', "%{$request->school_filter}%");
        }

        // Filter to users with valid phone numbers for SMS
        $users = $query->get()->filter(function ($user) {
            return $user->hasValidPhoneNumber();
        });

        if ($users->count() === 0) {
            return back()->with('error', 'No users match the selected criteria or have valid phone numbers.');
        }

        try {
            $result = $this->notificationService->sendBulkSms($users->toArray(), $request->message);

            if ($result['success']) {
                $message = "Bulk SMS sent successfully to {$users->count()} users.";
            } else {
                $message = "Bulk SMS failed: {$result['message']}";
            }

            return back()->with($result['success'] ? 'success' : 'error', $message);
        } catch (Exception $e) {
            Log::error('Failed to send bulk SMS: ' . $e->getMessage(), [
                'recipients' => $request->recipients,
                'school_filter' => $request->school_filter
            ]);
            return back()->with('error', 'Failed to send bulk SMS. Please check the logs.');
        }
    }

    public function getSmsBalance()
    {
        return response()->json([
            'balance' => 'N/A',
            'currency' => 'Credits'
        ]);
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