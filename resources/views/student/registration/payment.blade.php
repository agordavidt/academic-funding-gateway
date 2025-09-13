@extends('layouts.student')

@section('title', 'Payment')

@section('content')

@if($deadline)
    <div class="card text-center text-white bg-warning mb-3">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-clock me-2"></i> Application Deadline</h5>
            <p class="card-text fs-4">{{ $deadline->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>
@endif

<div class="step-indicator">
    <div class="step completed">1</div>
    <div class="step-connector completed"></div>
    <div class="step completed">2</div>
    <div class="step-connector completed"></div>
    <div class="step active">3</div>
</div>


<h4 class="text-center mb-4">Payment</h4>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Registration Fee:</strong> ₦3,000 (Non-refundable)
</div>

<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="bi bi-bank me-2"></i>Bank Transfer Details</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <p class="mb-2"><strong>Account Number:</strong> <span class="text-primary fs-5">1028614880</span></p>
                <p class="mb-2"><strong>Bank Name:</strong> UBA (United Bank for Africa)</p>
                <p class="mb-0"><strong>Account Name:</strong> Academic Funding Gateway Network</p>
            </div>            
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Upload Payment Receipt</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('student.payment.process') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group mb-4">
                <label for="payment_evidence" class="form-label">Payment Receipt *</label>
                <input type="file" class="form-control @error('payment_evidence') is-invalid @enderror" 
                       id="payment_evidence" name="payment_evidence" 
                       accept="image/*,application/pdf" required>
                @error('payment_evidence')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Upload your bank transfer receipt or screenshot. Supported formats: JPG, PNG, PDF (Max: 5MB)
                </small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-upload me-2"></i>Submit Payment Receipt
                </button>
            </div>
        </form>
    </div>
</div>

<div class="text-center mt-3">
    <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Profile
    </a>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('payment_evidence').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        if (fileSize > 5) {
            alert('File size must be less than 5MB');
            e.target.value = '';
            return;
        }
        
        // Show file name
        const fileName = file.name;
        const fileInfo = document.createElement('div');
        fileInfo.className = 'alert alert-info mt-2';
        fileInfo.innerHTML = '<i class="fas fa-file me-2"></i>Selected: ' + fileName;
        
        // Remove any existing file info
        const existingInfo = document.querySelector('.alert-info.mt-2');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        // Add new file info
        e.target.parentNode.appendChild(fileInfo);
    }
});
</script>
@endpush