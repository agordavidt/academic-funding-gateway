@extends('layouts.student')

@section('title', 'Registration Status')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
    </div>
    
    <h4 class="text-success mb-3">Registration Complete!</h4>
    
    <p class="lead mb-4">
        Thank you for completing your registration and submitting your payment evidence.
    </p>

    <div class="alert alert-info">
        <h6><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
        <ul class="mb-0 ps-3">
            <li><strong>Payment Verification:</strong> Your payment evidence will be verified within 24 hours</li>
            <li><strong>Application Review:</strong> After payment verification, your application will be reviewed by our team</li>
            <li>You will receive an email notification about your application status</li>
            <li>If accepted, you will receive instructions for accessing your training program</li>
            <li>Please keep your contact information updated</li>
        </ul>
    </div>

    <div class="alert alert-warning">
        <h6><i class="fas fa-clock me-2"></i>Processing Timeline</h6>
        <p class="mb-0">
            <strong>Payment Verification:</strong> Within 24 hours<br>
            <strong>Final Review:</strong> 2-3 business days after payment confirmation<br>
            <strong>Results Notification:</strong> Via email and SMS
        </p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h6>Need Help?</h6>
            <p class="mb-0">If you have any questions or concerns, please contact our support team.</p>
        </div>
    </div>
</div>
@endsection