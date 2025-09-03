<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        // Get the signature from the header
        $signature = $request->header('verif-hash');

        // Check if the signature matches the secret hash
        if (!$signature || ($signature !== config('flutterwave.secretHash'))) {
            // Silently ignore invalid requests
            Log::error('Invalid Flutterwave webhook signature.', ['signature' => $signature]);
            return response()->json(['status' => 'error'], 401);
        }

        // Get the event type and transaction details
        $payload = $request->all();
        $event = $payload['event'];

        if ($event === 'charge.completed') {
            $transactionId = $payload['data']['tx_ref'];
            $status = $payload['data']['status'];

            $payment = Payment::where('transaction_id', $transactionId)->first();

            if ($payment) {
                if ($status === 'successful') {
                    $payment->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'gateway_response' => $payload
                    ]);

                    $payment->user->update([
                        'payment_status' => 'paid',
                        'registration_stage' => 'completed'
                    ]);
                } else {
                    $payment->update([
                        'status' => 'failed',
                        'gateway_response' => $payload
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}