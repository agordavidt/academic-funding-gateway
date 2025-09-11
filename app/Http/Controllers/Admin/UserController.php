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
        $query = User::with(['application', 'payments']);

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

        // School/University filter
        if ($request->filled('school')) {
            $query->where('school', 'like', "%{$request->school}%");
        }

        // Registration stage filter
        if ($request->filled('registration_stage')) {
            $query->where('registration_stage', $request->registration_stage);
        }

        $users = $query->paginate(15);

        // Get unique schools for filter dropdown
        $schools = User::whereNotNull('school')
                        ->distinct()
                        ->pluck('school')
                        ->sort()
                        ->values();

        return view('admin.users.index', compact('users', 'schools'));
    }

    public function show(User $user)
    {
        $user->load(['application', 'payments', 'notifications', 'trainingInstitution']);
        
        // Get SMS balance for display
        $smsBalance = $this->smsService->getBalance();
        
        return view('admin.users.show', compact('user', 'smsBalance'));
    }

    public function updateApplicationStatus(Request $request, User $user)
    {
        $request->validate([
            'application_status' => 'required|in:pending,reviewing,accepted,rejected'
        ]);

        $oldStatus = $user->application_status;
        $user->update(['application_status' => $request->application_status]);

        // Send notification if status changed
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
    
        return back()->with('success', 'Application status updated successfully. No notification sent as status did not change.');
    }

    public function approvePayment(Request $request, User $user)
    {
        $payment = $user->payments()->where('status', 'submitted')->first();
        
        if (!$payment) {
            return back()->with('error', 'No pending payment found for this user.');
        }

        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
            'gateway_response' => array_merge($payment->gateway_response ?? [], [
                'approved_by' => auth('admin')->user()->name ?? 'Admin',
                'approved_at' => now(),
                'approval_note' => $request->approval_note
            ])
        ]);

        $user->update([
            'payment_status' => 'paid'
        ]);

        // Send payment approval notification (email + SMS)
        try {
            $this->notificationService->sendPaymentApproved($user, $payment);
            return back()->with('success', 'Payment approved successfully. User has been notified via email and SMS.');
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
            'gateway_response' => array_merge($payment->gateway_response ?? [], [
                'rejected_by' => auth('admin')->user()->name ?? 'Admin',
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ])
        ]);

        // Send payment rejection notification (email + SMS)
        try {
            $this->notificationService->sendPaymentRejected($user, $payment, $request->rejection_reason);
            return back()->with('success', 'Payment rejected. User has been notified via email and SMS.');
        } catch (Exception $e) {
            Log::error('Failed to send payment rejection notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'payment_id' => $payment->id
            ]);
            return back()->with('warning', 'Payment rejected, but notification failed. Error: ' . $e->getMessage());
        }
    }

    public function assignTrainingInstitution(Request $request, User $user)
    {
        $request->validate([
            'training_institution_id' => 'required|exists:training_institutions,id'
        ]);

        $trainingInstitution = \App\Models\TrainingInstitution::findOrFail($request->training_institution_id);
        
        $user->update([
            'training_institution_id' => $trainingInstitution->id,
            'training_assigned_at' => now()
        ]);

        // Send training assignment notification
        try {
            $this->notificationService->sendTrainingAssignment($user, $trainingInstitution);
            return back()->with('success', 'Training institution assigned successfully. User has been notified.');
        } catch (Exception $e) {
            Log::error('Failed to send training assignment notification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'institution_id' => $trainingInstitution->id
            ]);
            return back()->with('warning', 'Training institution assigned, but notification failed. Error: ' . $e->getMessage());
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
                // The service itself returned a failure, which is a handled error
                return back()->with('error', 'Failed to send SMS: ' . $result['message']);
            }
        } catch (Exception $e) {
            // A genuine unhandled exception occurred, like a network error
            Log::error('An exception occurred while trying to send custom SMS: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'phone' => $user->phone_number
            ]);
            return back()->with('error', 'An unexpected error occurred while sending SMS. Please check the logs.');
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

        // Apply recipient filters
        switch ($request->recipients) {
            case 'paid':
                $query->withPaymentStatus('paid');
                break;
            case 'pending':
                $query->withPaymentStatus('pending');
                break;
            case 'accepted':
                $query->withApplicationStatus('accepted');
                break;
            case 'rejected':
                $query->withApplicationStatus('rejected');
                break;
            case 'with_phone':
                $query->withValidPhone();
                break;
            case 'all':
                // No additional filter
                break;
        }

        // Apply school filter if specified
        if ($request->filled('school_filter')) {
            $query->fromSchool($request->school_filter);
        }

        // Always filter to users with valid phone numbers for SMS
        if ($request->recipients !== 'with_phone') {
            $query->withValidPhone();
        }

        $users = $query->get();

        if ($users->count() === 0) {
            return back()->with('error', 'No users match the selected criteria or have valid phone numbers.');
        }

        // Send bulk SMS
        try {
            $result = $this->notificationService->sendBulkSms($users->toArray(), $request->message);

            if ($result['success']) {
                $message = "Bulk SMS sent successfully to {$result['total']} users.";
            } else {
                $message = "Bulk SMS failed: {$result['message']}";
            }

            return back()->with($result['success'] ? 'success' : 'error', $message);
        } catch (Exception $e) {
            Log::error('An exception occurred while trying to send bulk SMS: ' . $e->getMessage(), [
                'recipients' => $request->recipients,
                'school_filter' => $request->school_filter
            ]);
            return back()->with('error', 'An unexpected error occurred while sending bulk SMS. Please check the logs.');
        }
    }

    public function getSmsBalance()
    {
        $balance = $this->smsService->getBalance();
        
        return response()->json([
            'balance' => $balance['balance'],
            'currency' => $balance['currency'] ?? 'Credits'
        ]);
    }

    public function smsSettings()
    {
        $balance = $this->smsService->getBalance();
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
            Log::error('An exception occurred while trying to send test SMS: ' . $e->getMessage(), [
                'phone' => $request->phone_number
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while sending the test SMS.'
            ], 500);
        }
    }
}