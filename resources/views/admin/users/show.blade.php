@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Student Details: {{ $user->full_name }}</div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" 
                                data-bs-target="#smsModal" data-user-id="{{ $user->id }}">
                            <i class="fas fa-sms me-2"></i>Send SMS
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Student Information</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Phone Number</strong></label>
                            <p>{{ $user->phone_number }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><strong>Email Address</strong></label>
                            <p>{{ $user->email ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><strong>School/University</strong></label>
                            <p>{{ $user->school ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><strong>Matriculation Number</strong></label>
                            <p>{{ $user->matriculation_number ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><strong>Address</strong></label>
                            <p>{{ $user->address ?: 'N/A' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><strong>Registration Stage</strong></label>
                            <p>
                                <span class="badge badge-info">
                                    {{ ucfirst(str_replace('_', ' ', $user->registration_stage)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($user->application)
                    <hr>
                    <div class="form-group">
                        <label class="form-label"><strong>Need Assessment</strong></label>
                        <div class="border rounded p-3 bg-light">
                            {{ $user->application->need_assessment_text ?: 'Not provided' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($user->payments()->where('payment_evidence', '!=', null)->exists())
        <!-- Payment Evidence Section -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-title">Payment Evidence</div>
            </div>
            <div class="card-body">
                @foreach($user->payments()->where('payment_evidence', '!=', null)->get() as $payment)
                <div class="border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Transaction ID: {{ $payment->transaction_id }}</h6>
                            <p><strong>Amount:</strong> ₦{{ number_format($payment->amount) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge badge-{{ $payment->status == 'success' ? 'success' : ($payment->status == 'rejected' ? 'danger' : ($payment->status == 'submitted' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            @if($payment->payment_note)
                            <p><strong>Note:</strong> {{ $payment->payment_note }}</p>
                            @endif
                            <p><strong>Uploaded:</strong> {{ $payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            @if($payment->payment_evidence)
                                @php
                                    $fileExtension = pathinfo($payment->payment_evidence, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp
                                
                                @if($isImage)
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $payment->payment_evidence) }}" 
                                             class="img-thumbnail" style="max-height: 150px; cursor: pointer;"
                                             data-bs-toggle="modal" data-bs-target="#imageModal{{ $payment->id }}">
                                        <br><small class="text-muted">Click to view full size</small>
                                    </div>

                                    <!-- Image Modal -->
                                    <div class="modal fade" id="imageModal{{ $payment->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Payment Evidence - {{ $payment->transaction_id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $payment->payment_evidence) }}" 
                                                         class="img-fluid" style="max-height: 80vh;">
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ asset('storage/' . $payment->payment_evidence) }}" 
                                                       class="btn btn-primary" target="_blank">
                                                        <i class="fas fa-download me-2"></i>Download
                                                    </a>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file-pdf text-danger" style="font-size: 3rem;"></i>
                                        <br>
                                        <a href="{{ asset('storage/' . $payment->payment_evidence) }}" 
                                           class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                            <i class="fas fa-eye me-1"></i>View PDF
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Fixed Payment Approval Section in user show view -->
                    @if($payment->status === 'submitted')
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <!-- Approval Form -->
                            <form method="POST" action="{{ route('admin.users.approve-payment', $user) }}" class="mb-2">
                                @csrf
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" name="approval_note" class="form-control" 
                                        placeholder="Approval note (optional)" maxlength="200">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i>Approve Payment
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Reject Button -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $payment->id }}">
                                <i class="fas fa-times me-1"></i>Reject Payment
                            </button>
                        </div>
                    </div>

                    <!-- Rejection Modal -->
                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reject Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.users.reject-payment', $user) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="rejectionReason{{ $payment->id }}" class="form-label">Reason for Rejection</label>
                                            <textarea class="form-control" id="rejectionReason{{ $payment->id }}" 
                                                    name="rejection_reason" rows="3" required 
                                                    placeholder="Please provide a clear reason for rejecting this payment..."></textarea>
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            The user will be notified via email/SMS about the rejection and reason.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times me-2"></i>Reject Payment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($payment->status === 'rejected' && isset($payment->gateway_response['rejection_reason']))
                    <div class="alert alert-danger mt-2">
                        <strong>Rejected:</strong> {{ $payment->gateway_response['rejection_reason'] }}
                        <br><small>Rejected by: {{ $payment->gateway_response['rejected_by'] ?? 'Admin' }} 
                        on {{ isset($payment->gateway_response['rejected_at']) ? \Carbon\Carbon::parse($payment->gateway_response['rejected_at'])->format('M d, Y H:i') : 'N/A' }}</small>
                    </div>
                    @endif

                    @if($payment->status === 'success' && isset($payment->gateway_response['approved_by']))
                    <div class="alert alert-success mt-2">
                        <strong>Approved</strong> by {{ $payment->gateway_response['approved_by'] }} 
                        on {{ isset($payment->gateway_response['approved_at']) ? \Carbon\Carbon::parse($payment->gateway_response['approved_at'])->format('M d, Y H:i') : 'N/A' }}
                        @if(isset($payment->gateway_response['approval_note']))
                        <br><strong>Note:</strong> {{ $payment->gateway_response['approval_note'] }}
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Application Status</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label class="form-label"><strong>Current Status</strong></label>
                        <div class="mb-2">
                            <span class="badge badge-{{ $user->application_status == 'accepted' ? 'success' : ($user->application_status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($user->application_status) }}
                            </span>
                        </div>
                        
                        <label class="form-label">Update Status</label>
                        <select name="application_status" class="form-control">
                            <option value="pending" {{ $user->application_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ $user->application_status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="accepted" {{ $user->application_status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $user->application_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Payment Information</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label"><strong>Payment Status</strong></label>
                    <div>
                        <span class="badge badge-{{ $user->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($user->payment_status) }}
                        </span>
                    </div>
                </div>
                
                @if($user->payments->count() > 0)
                    <hr>
                    <label class="form-label"><strong>Payment Summary</strong></label>
                    @php
                        $totalAmount = $user->payments->sum('amount');
                        $successfulPayments = $user->payments->where('status', 'success')->count();
                        $pendingPayments = $user->payments->where('status', 'submitted')->count();
                        $rejectedPayments = $user->payments->where('status', 'rejected')->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body p-2">
                                    <h6 class="mb-1">₦{{ number_format($totalAmount) }}</h6>
                                    <small class="text-muted">Total Amount</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-light">
                                <div class="card-body p-2">
                                    <h6 class="mb-1">{{ $user->payments->count() }}</h6>
                                    <small class="text-muted">Transactions</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($successfulPayments > 0)
                    <div class="mt-2">
                        <span class="badge badge-success">{{ $successfulPayments }} Successful</span>
                    </div>
                    @endif
                    @if($pendingPayments > 0)
                    <div class="mt-1">
                        <span class="badge badge-warning">{{ $pendingPayments }} Pending Review</span>
                    </div>
                    @endif
                    @if($rejectedPayments > 0)
                    <div class="mt-1">
                        <span class="badge badge-danger">{{ $rejectedPayments }} Rejected</span>
                    </div>
                    @endif
                @else
                    <p class="text-muted">No payment records found.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-title">Quick Actions</div>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" 
                            data-bs-target="#smsModal" data-user-id="{{ $user->id }}">
                        <i class="fas fa-sms me-2"></i>Send SMS
                    </button>
                    
                    @if($user->email)
                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-envelope me-2"></i>Send Email
                    </a>
                    @endif
                    
                    <a href="tel:{{ $user->phone_number }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-phone me-2"></i>Call Student
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send SMS to {{ $user->full_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.send-sms', $user) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Recipient</label>
                        <p class="form-control-static">{{ $user->full_name }} ({{ $user->phone_number }})</p>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" 
                                  maxlength="160" required placeholder="Enter your message..."></textarea>
                        <small class="form-text text-muted">
                            <span id="charCount">0</span>/160 characters
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Character count for SMS message
    $('#message').on('input', function() {
        const maxLength = 160;
        const currentLength = $(this).val().length;
        
        $('#charCount').text(currentLength);
        
        if (currentLength > maxLength) {
            $('#charCount').addClass('text-danger');
        } else {
            $('#charCount').removeClass('text-danger');
        }
    });

    // Image modal handling
    $('.img-thumbnail').on('click', function() {
        // Additional handling if needed
    });
});
</script>
@endpush