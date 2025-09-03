@extends('layouts.student')

@section('title', 'Payment Gateway')

@section('content')

<h4 class="text-center mb-4">Payment Gateway</h4>

<div class="alert alert-info text-center">
<i class="bi bi-info-circle me-2"></i>
<strong>Please do not refresh this page.</strong> The payment modal will appear shortly.
</div>

<div class="card mb-4">
<div class="card-body text-center">
<h5>Payment Summary</h5>
<hr>
<div class="row">
<div class="col-6">
<strong>Transaction ID:</strong>
</div>
<div class="col-6">
{{ $payment->transaction_id }}
</div>
</div>
<div class="row">
<div class="col-6">
<strong>Amount:</strong>
</div>
<div class="col-6">
₦{{ number_format($payment->amount) }}
</div>
</div>
<div class="row">
<div class="col-6">
<strong>Status:</strong>
</div>
<div class="col-6">
<span class="badge bg-warning">{{ ucfirst($payment->status) }}</span>
</div>
</div>
</div>
</div>

<div class="text-center">
<button type="button" class="btn btn-success btn-lg" id="payButton">
<i class="bi bi-credit-card me-2"></i>Pay Now
</button>
</div>

<div class="text-center mt-4">
<small class="text-muted">
This is powered by Flutterwave's secure payment gateway.
</small>
</div>
@endsection

@section('scripts')

<script src="https://www.google.com/search?q=https://checkout.flutterwave.com/v3.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
// Automatically click the pay button to open the modal on page load
document.getElementById('payButton').click();
});

const payButton = document.getElementById('payButton');
payButton.addEventListener('click', function() {
FlutterwaveCheckout({
public_key: "{{ config('flutterwave.publicKey') }}",
tx_ref: "{{ $payment->transaction_id }}",
amount: "{{ $payment->amount }}",
currency: "NGN",
country: "NG",
payment_options: "card,mobilemoney,ussd",
redirect_url: "{{ route('student.payment.verify') }}",
customer: {
email: "{{ $payment->user->email }}",
phone_number: "{{ $payment->user->phone_number }}",
name: "{{ $payment->user->first_name }} {{ $payment->user->last_name }}",
},
customizations: {
title: "Academic Funding Gateway",
description: "Payment for registration fee",
logo: "https://www.google.com/search?q=https://via.placeholder.com/150",
},
});
});
</script>

@endsection