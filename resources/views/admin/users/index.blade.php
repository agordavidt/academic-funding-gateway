@extends('layouts.admin')

@section('title', 'Students Management')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Students Management</div>
                    <div class="card-tools">
                        <button type="button" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#bulkSmsModal">
                            <i class="fas fa-sms me-2"></i>Bulk SMS
                        </button>
                        <a href="{{ route('admin.import.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-import me-2"></i>Import Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h4 class="card-title">All Students ({{ $users->total() }})</h4>
            <div class="ml-auto">
                <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Search students..." value="{{ request('search') }}" style="width: 200px;">
                    
                    <select name="payment_status" class="form-control form-control-sm" style="width: 130px;">
                        <option value="">All Payments</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    
                    <select name="application_status" class="form-control form-control-sm" style="width: 140px;">
                        <option value="">All Applications</option>
                        <option value="pending" {{ request('application_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewing" {{ request('application_status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="accepted" {{ request('application_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('application_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <select name="school" class="form-control form-control-sm" style="width: 180px;">
                        <option value="">All Universities</option>
                        @foreach($schools as $school)
                            <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                {{ Str::limit($school, 25) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="registration_stage" class="form-control form-control-sm" style="width: 140px;">
                        <option value="">All Stages</option>
                        <option value="imported" {{ request('registration_stage') == 'imported' ? 'selected' : '' }}>Imported</option>
                        <option value="profile_completion" {{ request('registration_stage') == 'profile_completion' ? 'selected' : '' }}>Profile</option>
                        <option value="payment" {{ request('registration_stage') == 'payment' ? 'selected' : '' }}>Payment</option>
                        <option value="completed" {{ request('registration_stage') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>

                    @if(request()->hasAny(['search', 'payment_status', 'application_status', 'school', 'registration_stage']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>School</th>
                        <th>Payment</th>
                        <th>Application</th>
                        <th>Registration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->email ?: 'N/A' }}</td>
                            <td title="{{ $user->school }}">{{ Str::limit($user->school ?: 'N/A', 20) }}</td>
                            <td>
                                @if($user->payment_status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->application_status == 'accepted' ? 'success' : ($user->application_status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($user->application_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucfirst(str_replace('_', ' ', $user->registration_stage)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" 
                                            data-bs-target="#smsModal" data-user-id="{{ $user->id }}" 
                                            data-user-name="{{ $user->full_name }}" 
                                            data-user-phone="{{ $user->phone_number }}" title="Send SMS">
                                        <i class="fas fa-sms"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Single SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send SMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="smsForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Recipient</label>
                        <p class="form-control-static" id="smsRecipient"></p>
                    </div>
                    <div class="form-group">
                        <label for="smsMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="smsMessage" name="message" rows="4" 
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

<!-- Bulk SMS Modal -->
<div class="modal fade" id="bulkSmsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Bulk SMS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.bulk-sms') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recipients" class="form-label">Recipients</label>
                                <select class="form-control" id="recipients" name="recipients" required>
                                    <option value="">Select recipient group</option>
                                    <option value="all">All Users</option>
                                    <option value="paid">Paid Users Only</option>
                                    <option value="pending">Pending Payment Users</option>
                                    <option value="accepted">Accepted Applications</option>
                                    <option value="rejected">Rejected Applications</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="schoolFilter" class="form-label">University Filter (Optional)</label>
                                <select class="form-control" id="schoolFilter" name="school_filter">
                                    <option value="">All Universities</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school }}">{{ $school }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bulkMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="bulkMessage" name="message" rows="4" 
                                  maxlength="160" required placeholder="Enter your bulk message..."></textarea>
                        <small class="form-text text-muted">
                            <span id="bulkCharCount">0</span>/160 characters
                        </small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This will send SMS to all users matching the selected criteria. 
                        Please ensure your message is appropriate and necessary.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-broadcast-tower me-2"></i>Send Bulk SMS
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
    // SMS Modal handling
    $('#smsModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const userId = button.data('user-id');
        const userName = button.data('user-name');
        const userPhone = button.data('user-phone');
        
        const modal = $(this);
        modal.find('#smsRecipient').text(userName + ' (' + userPhone + ')');
        modal.find('#smsForm').attr('action', '/admin/users/' + userId + '/sms');
    });

    // Character count for SMS messages
    $('#smsMessage, #bulkMessage').on('input', function() {
        const maxLength = 160;
        const currentLength = $(this).val().length;
        const countElement = $(this).is('#smsMessage') ? '#charCount' : '#bulkCharCount';
        
        $(countElement).text(currentLength);
        
        if (currentLength > maxLength) {
            $(countElement).addClass('text-danger');
        } else {
            $(countElement).removeClass('text-danger');
        }
    });

    // Auto-submit form filters on change (optional)
    $('.form-control-sm[name="payment_status"], .form-control-sm[name="application_status"], .form-control-sm[name="school"], .form-control-sm[name="registration_stage"]').on('change', function() {
        // Uncomment below line if you want auto-submit on filter change
        // $(this).closest('form').submit();
    });
});
</script>
@endpush