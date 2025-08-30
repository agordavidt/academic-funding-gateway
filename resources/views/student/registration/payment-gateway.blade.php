@extends('layouts.student')

@section('title', 'Payment Gateway')

@section('content')
<h4 class="text-center mb-4">Payment Gateway</h4>

<div class="alert alert-warning text-center">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Demo Payment Gateway</strong><br>
    This is a placeholder payment implementation for testing purposes.
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
    <p class="mb-3">Choose payment outcome for testing:</p>
    
    <form method="POST" action="{{ route('student.payment.confirm', $payment) }}" class="d-inline me-3">
        @csrf
        <input type="hidden" name="simulate_success" value="1">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle me-2"></i>Simulate Successful Payment
        </button>
    </form>

    <form method="POST" action="{{ route('student.payment.confirm', $payment) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-x-circle me-2"></i>Simulate Failed Payment
        </button>
    </form>
</div>

<div class="text-center mt-4">
    <small class="text-muted">
        In production, this will redirect to the actual Flutterwave payment gateway.
    </small>
</div>
@endsection