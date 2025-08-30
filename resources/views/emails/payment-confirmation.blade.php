@extends('emails.layout')

@section('title', 'Payment Confirmation')

@section('content')
<h2>Dear {{ $user->full_name }},</h2>

<div class="alert alert-success">
    <h3>✅ Payment Confirmed!</h3>
    <p>Your payment has been successfully processed and your registration is now complete.</p>
</div>

<p>Thank you for completing your registration with the Academic Funding Gateway. We have received your payment and your application is now in our system.</p>

<h4>Payment Details:</h4>
<ul>
    <li><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</li>
    <li><strong>Amount:</strong> ₦{{ number_format($payment->amount) }}</li>
    <li><strong>Payment Date:</strong> {{ $payment->paid_at->format('M d, Y \a\t H:i A') }}</li>
    <li><strong>Status:</strong> {{ ucfirst($payment->status) }}</li>
</ul>

<h4>What's Next?</h4>
<ol>
    <li><strong>Application Review:</strong> Your application will be reviewed by our assessment team</li>
    <li><strong>Status Updates:</strong> You will receive email notifications about your application status</li>
    <li><strong>Decision Timeline:</strong> Expect to hear from us within 2-3 weeks</li>
    <li><strong>Further Instructions:</strong> If accepted, you'll receive detailed enrollment information</li>
</ol>

<div class="alert alert-info">
    <h4>Important Reminders:</h4>
    <ul>
        <li>Keep this email as proof of payment</li>
        <li>The registration fee of ₦3,000 is non-refundable</li>
        <li>Ensure your contact information remains current</li>
        <li>Check your email regularly for updates</li>
    </ul>
</div>

<h4>Your Registration Summary:</h4>
<ul>
    <li><strong>Name:</strong> {{ $user->full_name }}</li>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Phone:</strong> {{ $user->phone_number }}</li>
    <li><strong>School:</strong> {{ $user->school }}</li>
    <li><strong>Registration Status:</strong> {{ ucfirst(str_replace('_', ' ', $user->registration_stage)) }}</li>
</ul>

<p>We appreciate your trust in the Academic Funding Gateway and look forward to potentially supporting your academic journey.</p>

<p>If you have any questions about your payment or registration, please contact our support team.</p>

<p>Best regards,<br>
<strong>Academic Funding Gateway Team</strong></p>
@endsection