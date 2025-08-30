@extends('layouts.admin')

@section('title', 'Student Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">{{ $user->full_name }}</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
                        <p><strong>Email:</strong> {{ $user->email ?: 'N/A' }}</p>
                        <p><strong>School:</strong> {{ $user->school ?: 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Matriculation Number:</strong> {{ $user->matriculation_number ?: 'N/A' }}</p>
                        <p><strong>Address:</strong> {{ $user->address ?: 'N/A' }}</p>
                        <p><strong>Registration Stage:</strong> {{ ucfirst(str_replace('_', ' ', $user->registration_stage)) }}</p>
                    </div>
                </div>

                @if($user->application)
                    <hr>
                    <h6>Need Assessment</h6>
                    <p>{{ $user->application->need_assessment_text ?: 'Not provided' }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Application Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Current Status:</label>
                        <span class="badge bg-{{ $user->application_status == 'accepted' ? 'success' : ($user->application_status == 'rejected' ? 'danger' : 'warning') }} d-block mb-2">
                            {{ ucfirst($user->application_status) }}
                        </span>
                        
                        <select name="application_status" class="form-select">
                            <option value="pending" {{ $user->application_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ $user->application_status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="accepted" {{ $user->application_status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $user->application_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Payment Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $user->payment_status == 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($user->payment_status) }}
                    </span>
                </p>
                
                @if($user->payments->count() > 0)
                    <h6 class="mt-3">Payment History</h6>
                    @foreach($user->payments as $payment)
                        <div class="small">
                            <strong>₦{{ number_format($payment->amount) }}</strong> - 
                            <span class="badge bg-{{ $payment->status == 'success' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                {{ $payment->status }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        <hr>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
