<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('student.registration.phone-verification');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|max:15'
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return back()->withErrors(['phone_number' => 'Phone number not found in our records.']);
        }

        if ($user->registration_stage === 'completed') {
            return redirect()->route('student.status')->with('info', 'Your registration is already complete.');
        }

        // Store user ID in session for the registration process
        session(['registration_user_id' => $user->id]);

        return redirect()->route('student.profile');
    }

    public function showProfile()
    {
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('student.register');
        }

        $user = User::findOrFail($userId);
        $application = $user->application ?: new Application();

        return view('student.registration.profile', compact('user', 'application'));
    }

    public function updateProfile(Request $request)
    {
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('student.register');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'required|string|max:500',
            'school' => 'required|string|max:255',
            'matriculation_number' => 'nullable|string|max:50',
            'need_assessment_text' => 'required|string|max:1000',
        ]);

        // Update user information
        $user->update([
            'email' => $request->email,
            'address' => $request->address,
            'school' => $request->school,
            'matriculation_number' => $request->matriculation_number,
            'registration_stage' => 'payment',
        ]);

        // Create or update application
        $user->application()->updateOrCreate(
            ['user_id' => $user->id],
            ['need_assessment_text' => $request->need_assessment_text]
        );

        return redirect()->route('student.payment');
    }

    public function showPayment()
    {
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('student.register');
        }

        $user = User::findOrFail($userId);

        if ($user->registration_stage !== 'payment') {
            return redirect()->route('student.profile');
        }

        if ($user->payment_status === 'paid') {
            return redirect()->route('student.status');
        }

        // Create payment record if it doesn't exist
        $payment = Payment::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'transaction_id' => 'TXN_' . time() . '_' . $user->id,
                'amount' => 3000.00,
                'status' => 'pending',
            ]
        );

        return view('student.registration.payment', compact('user', 'payment'));
    }

    public function processPayment(Request $request)
    {
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('student.register');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'terms_agreed' => 'required|accepted',
            'payment_evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'payment_note' => 'nullable|string|max:500',
        ]);

        // Update terms agreement
        $user->application->update([
            'terms_agreed_at' => now()
        ]);

        // Handle file upload
        $evidencePath = null;
        if ($request->hasFile('payment_evidence')) {
            $evidencePath = $request->file('payment_evidence')->store('payment_evidence', 'public');
        }

        // Find or create payment record
        $payment = Payment::where('user_id', $user->id)->where('status', 'pending')->first();
        if (!$payment) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => 'TXN_' . time() . '_' . $user->id,
                'amount' => 3000.00,
                'status' => 'pending',
            ]);
        }

        // Update payment with evidence
        $payment->update([
            'payment_evidence' => $evidencePath,
            'payment_note' => $request->payment_note,
            'status' => 'submitted', // New status for submitted evidence
            'gateway_response' => [
                'evidence_uploaded' => true,
                'uploaded_at' => now(),
                'file_type' => $request->file('payment_evidence')->getClientOriginalExtension()
            ]
        ]);

        $user->update([
            'registration_stage' => 'completed'
        ]);

        // Send notification email if needed
        if ($user->email) {
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->sendPaymentSubmittedConfirmation($user, $payment);
        }

        session()->forget('registration_user_id');
        return redirect()->route('student.status')->with('success', 'Payment evidence submitted successfully! Your application will be subject to review within 24 hours.');
    }

    public function status()
    {
        return view('student.registration.status');
    }
}