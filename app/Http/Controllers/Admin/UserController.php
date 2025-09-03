<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
        $user->load(['application', 'payments', 'notifications']);
        return view('admin.users.show', compact('user'));
    }

    public function updateApplicationStatus(Request $request, User $user)
    {
        $request->validate([
            'application_status' => 'required|in:pending,reviewing,accepted,rejected'
        ]);

        $oldStatus = $user->application_status;
        $user->update(['application_status' => $request->application_status]);

        // Send notification if status changed and user has email
        if ($oldStatus !== $request->application_status && $user->email) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendApplicationStatusUpdate($user, $request->application_status);
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

        // Send payment confirmation notification
        if ($user->email) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendPaymentApproved($user, $payment);
        }

        return back()->with('success', 'Payment approved successfully.');
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

        // Send payment rejection notification
        if ($user->email) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendPaymentRejected($user, $payment, $request->rejection_reason);
        }

        return back()->with('success', 'Payment rejected. User has been notified.');
    }

    public function sendSms(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:160'
        ]);

        // Here you would integrate with your SMS service
        // For now, we'll just log it or store in notifications
        
        // Example implementation - you'll need to integrate with actual SMS service
        $smsService = app(\App\Services\SmsService::class);
        $result = $smsService->sendSms($user->phone_number, $request->message);

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
            'recipients' => 'required|in:all,paid,pending,accepted,rejected',
            'school_filter' => 'nullable|string'
        ]);

        $query = User::whereNotNull('phone_number');

        // Apply recipient filters
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
        }

        // Apply school filter if specified
        if ($request->filled('school_filter')) {
            $query->where('school', 'like', "%{$request->school_filter}%");
        }

        $users = $query->get();

        if ($users->count() === 0) {
            return back()->with('error', 'No users match the selected criteria.');
        }

        // Send SMS to all matching users
        $smsService = app(\App\Services\SmsService::class);
        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            $result = $smsService->sendSms($user->phone_number, $request->message);
            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $message = "Bulk SMS completed. Sent: {$successCount}, Failed: {$failCount}";
        return back()->with('success', $message);
    }
}