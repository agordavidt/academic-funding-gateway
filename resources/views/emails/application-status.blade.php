@extends('emails.layout')

@section('title', 'Application Status Update')

@section('content')
<h2>Dear {{ $user->full_name }},</h2>

@if($status === 'accepted')
    <div class="alert alert-success">
        <h3>🎉 Congratulations!</h3>
        <p>We are pleased to inform you that your grant application has been <strong>accepted</strong>!</p>
    </div>
    
    <p>This is an exciting milestone in your academic journey. Your application has been carefully reviewed and we believe you are an excellent candidate for our grant program.</p>
    
    <h4>What happens next?</h4>
    <ul>
        <li>You will receive detailed instructions about your training program within 3-5 business days</li>
        <li>Our team will contact you to coordinate enrollment with our partner institutions</li>
        <li>Please ensure your contact information is up to date</li>
        <li>Keep an eye on your email for further communications</li>
    </ul>
    
    <p><strong>Grant Details:</strong></p>
    <ul>
        <li>Value: Up to ₦500,000</li>
        <li>Type: Full scholarship to approved training program</li>
        <li>Duration: As specified by the selected program</li>
    </ul>

@elseif($status === 'rejected')
    <div class="alert alert-warning">
        <h3>Application Update</h3>
        <p>After careful consideration, we regret to inform you that your grant application was not selected for this round.</p>
    </div>
    
    <p>We received many excellent applications, making the selection process highly competitive. While your application was not selected this time, we encourage you to:</p>
    
    <ul>
        <li>Apply for future grant opportunities</li>
        <li>Continue pursuing your academic and professional goals</li>
        <li>Stay connected with our programs for upcoming opportunities</li>
    </ul>
    
    <p>Thank you for your interest in the Academic Funding Gateway. We wish you the best in your academic endeavors.</p>

@elseif($status === 'reviewing')
    <div class="alert alert-info">
        <h3>Application Under Review</h3>
        <p>Your grant application is currently being reviewed by our assessment team.</p>
    </div>
    
    <p>We have received your complete application and it is now in the review process. Our team is carefully evaluating all submissions to ensure fair consideration.</p>
    
    <h4>Review Process Timeline:</h4>
    <ul>
        <li>Initial review: 5-7 business days</li>
        <li>Detailed assessment: 7-10 business days</li>
        <li>Final decision: You will be notified within 2-3 weeks</li>
    </ul>
    
    <p><strong>Please note:</strong> No action is required from you at this time. We will contact you once the review is complete.</p>
@endif

<hr>

<p><strong>Your Application Details:</strong></p>
<ul>
    <li><strong>Name:</strong> {{ $user->full_name }}</li>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Phone:</strong> {{ $user->phone_number }}</li>
    <li><strong>School:</strong> {{ $user->school }}</li>
    <li><strong>Application Status:</strong> {{ ucfirst($status) }}</li>
</ul>

<p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>

<p>Best regards,<br>
<strong>Academic Funding Gateway Team</strong></p>
@endsection