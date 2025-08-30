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
                            <label class="form-label"><strong>School</strong></label>
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
                            <p>{{ ucfirst(str_replace('_', ' ', $user->registration_stage)) }}</p>
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
                    <label class="form-label"><strong>Payment History</strong></label>
                    @foreach($user->payments as $payment)
                        <div class="border rounded p-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>₦{{ number_format($payment->amount) }}</strong>
                                <span class="badge badge-{{ $payment->status == 'success' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <small class="text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection