@extends('layouts.student')

@section('title', 'Phone Verification')

@section('content')
<div class="step-indicator">
    <div class="step active">1</div>
    <div class="step-connector"></div>
    <div class="step">2</div>
    <div class="step-connector"></div>
    <div class="step">3</div>
</div>

<h4 class="text-center mb-4">Verify Your Phone Number</h4>

<form method="POST" action="{{ route('student.verify-phone') }}">
    @csrf
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
               id="phone_number" name="phone_number" 
               placeholder="Enter your registered phone number" 
               value="{{ old('phone_number') }}" required>
        @error('phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Enter the phone number you provided during data collection</div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Continue
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <small class="text-muted">
        Don't have your phone number registered? Contact support for assistance.
    </small>
</div>
@endsection