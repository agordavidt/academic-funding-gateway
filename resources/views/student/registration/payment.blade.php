@extends('layouts.student')

@section('title', 'Payment')

@section('content')
<div class="step-indicator">
    <div class="step completed">1</div>
    <div class="step-connector completed"></div>
    <div class="step completed">2</div>
    <div class="step-connector completed"></div>
    <div class="step active">3</div>
</div>

<h4 class="text-center mb-4">Payment & Terms</h4>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Registration Fee:</strong> ₦3,000 (Non-refundable)
</div>

<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Grant Information</h6>
    </div>
    <div class="card-body">
        <ul class="mb-0">
            <li>Grant value: Up to ₦500,000</li>
            <li>Provided as full scholarship to training programs</li>
            <li>No cash disbursement</li>
            <li>Subject to application review and acceptance</li>
        </ul>
    </div>
</div>

<form method="POST" action="{{ route('student.payment.process') }}">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">Terms and Conditions</h6>
        </div>
        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <h6>Academic Funding Gateway - Terms and Conditions</h6>
            
            <p><strong>1. Grant Nature</strong></p>
            <p>The Academic Funding Gateway provides grants in the form of paid access to approved training programs. Grants are not provided as cash payments but as full scholarships to partner institutions.</p>
            
            <p><strong>2. Registration Fee</strong></p>
            <p>A non-refundable registration fee of ₦3,000 is required to complete the application process. This fee is not deductible from the grant amount.</p>
            
            <p><strong>3. Application Review</strong></p>
            <p>All applications are subject to review. Acceptance is not guaranteed and depends on available slots, eligibility criteria, and assessment results.</p>
            
            <p><strong>4. Grant Utilization</strong></p>
            <p>Accepted applicants must utilize their grants within the specified timeframe and at designated partner institutions.</p>
            
            <p><strong>5. Data Usage</strong></p>
            <p>Personal information provided will be used for application processing and communication purposes only.</p>
            
            <p><strong>6. Modifications</strong></p>
            <p>The organization reserves the right to modify these terms as necessary.</p>
        </div>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input @error('terms_agreed') is-invalid @enderror" 
               type="checkbox" id="terms_agreed" name="terms_agreed" value="1">
        <label class="form-check-label" for="terms_agreed">
            I have read and agree to the Terms and Conditions
        </label>
        @error('terms_agreed')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-credit-card me-2"></i>Proceed to Payment (₦3,000)
        </button>
    </div>
</form>

<div class="text-center mt-3">
    <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Profile
    </a>
</div>
@endsection