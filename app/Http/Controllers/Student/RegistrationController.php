<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        // Clean the phone number using the model method
        $cleanPhoneNumber = User::cleanPhoneNumberStatic($request->phone_number);
        
        $user = User::where('phone_number', $cleanPhoneNumber)->first();

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

        return view('student.registration.profile', compact('user'));
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
            'school' => 'nullable|string|max:255',
        ]);

        // Update user information
        $user->update([
            'email' => $request->email,
            'school' => $request->school,
            'registration_stage' => 'payment',
        ]);

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

        // Check for payment status
        $payment = Payment::where('user_id', $user->id)->first();
        if ($payment && $payment->status === 'submitted') {
            return redirect()->route('student.status')->with('info', 'Your payment evidence has already been submitted and is pending review.');
        }
        if ($payment && $payment->status === 'success') {
             return redirect()->route('student.status')->with('info', 'Your payment has been approved. Your application is now complete.');
        }

        // Fetch the application deadline from the database
        $deadlineSetting = Setting::where('key', 'application_deadline')->first();
        $deadline = $deadlineSetting ? Carbon::parse($deadlineSetting->value) : null;
        
        // Pass the deadline to the view
        return view('student.registration.payment', compact('user', 'deadline'));
    }

    public function processPayment(Request $request)
    {
        try {
            $userId = session('registration_user_id');
            if (!$userId) {
                return redirect()->route('student.register');
            }

            $user = User::findOrFail($userId);

            $request->validate([
                'payment_evidence' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // Handle file upload with proper error handling
            $evidencePath = null;
            if ($request->hasFile('payment_evidence')) {
                $file = $request->file('payment_evidence');
                
                // Create a unique filename
                $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                
                // Store the file
                $evidencePath = $file->storeAs('payment_evidence', $filename, 'public');
                
                Log::info('Payment evidence uploaded', [
                    'user_id' => $user->id,
                    'filename' => $filename,
                    'path' => $evidencePath
                ]);
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
                'status' => 'submitted',
                'gateway_response' => json_encode([
                    'evidence_uploaded' => true,
                    'uploaded_at' => now()->toISOString(),
                    'file_type' => $request->file('payment_evidence')->getClientOriginalExtension()
                ])
            ]);

            // Update user registration stage
            $user->update([
                'registration_stage' => 'completed'
            ]);
            
            // Redirect to the status page. The session ID is NOT forgotten here.
            return redirect()->route('student.status')->with('success', 'Payment evidence submitted successfully! Your application will be reviewed within 24 hours.');
            
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'user_id' => $userId ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['payment_evidence' => 'Error uploading payment evidence. Please try again.']);
        }
    }

    public function status()
    {
        $userId = session('registration_user_id');
        
        // This check is necessary to prevent users from navigating directly here.
        if (!$userId) {
            return redirect()->route('student.register');
        }

        $user = User::findOrFail($userId);
        $payment = $user->payments()->latest()->first();
        
        // Forget the session user ID AFTER it has been successfully used.
        session()->forget('registration_user_id');

        return view('student.registration.status', compact('user', 'payment'));
    }
}
