<?php


namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        return view('student.registration.payment', compact('user'));
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
        ]);

        // Update terms agreement
        $user->application->update([
            'terms_agreed_at' => now()
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'transaction_id' => 'TXN_' . time() . '_' . $user->id,
            'amount' => 3000.00,
            'status' => 'pending',
        ]);

        // Redirect to placeholder payment gateway
        return redirect()->route('student.payment.gateway', ['payment' => $payment]);
    }

    public function paymentGateway(Payment $payment)
    {
        return view('student.registration.payment-gateway', compact('payment'));
    }

    public function confirmPayment(Request $request, Payment $payment)
    {
        // Placeholder payment processing
        $success = $request->has('simulate_success');

        if ($success) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
                'gateway_response' => ['status' => 'success', 'reference' => 'REF_' . time()]
            ]);

            $payment->user->update([
                'payment_status' => 'paid',
                'registration_stage' => 'completed'
            ]);

            // Send payment confirmation email
            if ($payment->user->email) {
                $notificationService = app(\App\Services\NotificationService::class);
                $notificationService->sendPaymentConfirmation($payment->user, $payment);
            }

            session()->forget('registration_user_id');
            return redirect()->route('student.status')->with('success', 'Payment successful! Your registration is complete.');
        } else {
            $payment->update([
                'status' => 'failed',
                'gateway_response' => ['status' => 'failed', 'message' => 'Payment failed']
            ]);

            return redirect()->route('student.payment')->with('error', 'Payment failed. Please try again.');
        }
    }

    public function status()
    {
        return view('student.registration.status');
    }
}