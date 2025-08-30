<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        // TODO: Implement actual Flutterwave webhook handling
        // This is a placeholder for the real implementation
        
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status');
        
        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if ($payment) {
            if ($status === 'successful') {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                    'gateway_response' => $request->all()
                ]);

                $payment->user->update([
                    'payment_status' => 'paid',
                    'registration_stage' => 'completed'
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'gateway_response' => $request->all()
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}