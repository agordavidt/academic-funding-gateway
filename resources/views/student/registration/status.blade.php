@extends('layouts.student')

@section('title', 'Registration Status')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
    </div>
    
    <h4 class="text-success mb-3">Registration Complete!</h4>   

    
<!-- 
    <div class="alert alert-warning">
        <h6><i class="fas fa-clock me-2"></i>Processing Timeline</h6>
        <p class="mb-0">
            <strong>Payment Verification:</strong> Within 24 hours<br>
            <strong>Final Review:</strong> 2-3 business days after payment confirmation<br>
            <strong>Results Notification:</strong> Via email and SMS
        </p>
    </div> -->

    <div class="card mt-4">
        <div class="card-body">
            <h6>Need Help?</h6>
            <p class="mb-0">If you have any questions or concerns, please contact <span style="color: blue;">info@academicfunding.org</span> </p>
        </div>
    </div>
</div>
@endsection