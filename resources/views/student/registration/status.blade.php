@extends('layouts.student')

@section('title', 'Registration Status')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
    </div>
    
    <h4 class="text-success mb-3">Registration Complete!</h4>
    
    <p class="lead mb-4">
        Thank you for completing your registration. Your application has been submitted successfully.
    </p>

    <div class="alert alert-info">
        <h6><i class="fas fa-info-circle me-2"></i>What's Next?</h6>
        <ul class="mb-0 ps-3">
            <li>Your application will be reviewed by our team</li>
            <li>You will receive an email notification about your application status</li>
            <li>If accepted, you will receive instructions for accessing your training program</li>
            <li>Please keep your contact information updated</li>
        </ul>
    </div>

    <div class="mt-4">
        <a href="{{ route('student.register') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Register Another Application
        </a>
    </div>
</div>
@endsection