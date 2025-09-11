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



<form method="POST" action="{{ route('student.verify-phone') }}">
    @csrf
    <div class="form-group">
        <label for="phone_number" class="form-label">Phone Number</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-phone text-primary"></i>
            </span>
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                   id="phone_number" name="phone_number" 
                   placeholder="Enter your registered phone number" 
                   value="{{ old('phone_number') }}" required>
        </div>
        @error('phone_number')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Enter the phone number you provided during data collection</small>
    </div>

    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-arrow-right me-2"></i>Continue
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <small class="text-muted">
        Don't have your phone number registered? Contact support for assistance.
    </small>
</div>
@endsection