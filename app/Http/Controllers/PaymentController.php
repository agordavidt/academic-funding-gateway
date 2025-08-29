<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\FlutterwaveService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $flutterwaveService;
    protected $notificationService;
    
    public function __construct(FlutterwaveService $flutterwaveService, NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->flutterwaveService = $flutterwaveService;
        $this->notificationService = $notificationService;
    }
    
    public function show()
    {
        $user = Auth::user();
        
        if (!$user->hasCompletedProfile()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please complete your profile before making payment.');
        }
        
        if ($user->hasPaidFee()) {
            return redirect()->route('dashboard')
                ->with('info', 'Payment already completed.');
        }
        
        return view('payment.show', [
            'user' => $user,
            'amount' => config('funding.acceptance_fee'),
            'publicKey' => config('funding.flutterwave.public_key')
        ]);
    }
    
    public function initialize(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasCompletedProfile()) {
            return response()->json(['error' => 'Profile not completed'], 400);
        }
        
        if ($user->hasPaidFee()) {
            return response()->json(['error' => 'Payment already completed'], 400);
        }
        
        try {
            DB::beginTransaction();
            
            $transactionId = 'TXN_' . time() . '_' . Str::random(10);
            
            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'transaction_id' => $transactionId,
                'flutterwave_ref' => $transactionId,
                'amount' => config('funding.acceptance_fee'),
                'currency' => 'NGN',
                'status' => 'pending'
            ]);
            
            // Initialize Flutterwave payment
            $paymentData = $this->flutterwaveService->initializePayment([
                'tx_ref' => $transactionId,
                'amount' => config('funding.acceptance_fee'),
                'currency' => 'NGN',
                'customer' => [
                    'email' => $user->email,
                    'phonenumber' => $user->phone_number,
                    'name' => $user->full_name
                ],
                'customizations' => [
                    'title' => 'Academic Funding Gateway',
                    'description' => 'Acceptance Fee Payment',
                    'logo' => asset('images/logo.png')
                ],
                'redirect_url' => route('payment.callback')
            ]);
            
            DB::commit();
            
            return response()->json([
                'payment_link' => $paymentData['data']['link'],
                'transaction_id' => $transactionId
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Payment initialization failed. Please try again.'
            ], 500);
        }
    }
    
    public function callback(Request $request)
    {
        $transactionId = $request->query('tx_ref');
        $status = $request->query('status');
        
        if (!$transactionId) {
            return redirect()->route('dashboard')
                ->with('error', 'Invalid payment callback.');
        }
        
        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if (!$payment) {
            return redirect()->route('dashboard')
                ->with('error', 'Payment record not found.');
        }
        
        try {
            // Verify payment with Flutterwave
            $verificationResult = $this->flutterwaveService->verifyPayment($transactionId);
            
            DB::beginTransaction();
            
            if ($verificationResult['status'] === 'success') {
                // Update payment status
                $payment->update([
                    'status' => 'success',
                    'payment_method' => $verificationResult['data']['payment_type'] ?? null,
                    'gateway_response' => $verificationResult['data'],
                    'paid_at' => now()
                ]);
                
                // Update user payment status
                $payment->user->update([
                    'payment_status' => 'paid',
                    'payment_completed_at' => now()
                ]);
                
                DB::commit();
                
                // Send notifications (with error handling)
                try {
                    $this->notificationService->sendPaymentConfirmation($payment->user);
                } catch (\Exception $e) {
                    // Log error but don't break the flow
                    \Log::error('Payment notification failed', [
                        'user_id' => $payment->user->id,
                        'error' => $e->getMessage()
                    ]);
                }
                
                return redirect()->route('dashboard')
                    ->with('success', 'Payment successful! You can now submit your grant application.');
                    
            } else {
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $verificationResult['data'] ?? null
                ]);
                
                DB::commit();
                
                return redirect()->route('payment.show')
                    ->with('error', 'Payment verification failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('dashboard')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }
}