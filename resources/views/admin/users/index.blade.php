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
            <h4 class="card-title">All Students</h4>
            <div class="ml-auto">
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search students..." value="{{ request('search') }}">
                        <select name="payment_status" class="form-control">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <select name="application_status" class="form-control">
                            <option value="">All Applications</option>
                            <option value="pending" {{ request('application_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ request('application_status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="accepted" {{ request('application_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('application_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
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
                        <th>Payment Status</th>
                        <th>Application Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->email ?: 'N/A' }}</td>
                            <td>{{ $user->school ?: 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $user->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->application_status == 'accepted' ? 'success' : ($user->application_status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($user->application_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection