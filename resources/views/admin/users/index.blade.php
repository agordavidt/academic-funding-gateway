@extends('layouts.admin')

@section('title', 'Students Management')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">Students Management</h1>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">All Students</h5>
            </div>
            <div class="col-auto">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Search students..." value="{{ request('search') }}">
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">All Payments</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    <select name="application_status" class="form-select form-select-sm">
                        <option value="">All Applications</option>
                        <option value="pending" {{ request('application_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewing" {{ request('application_status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="accepted" {{ request('application_status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('application_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Filter</button>
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
                                <span class="badge bg-{{ $user->payment_status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($user->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->application_status == 'accepted' ? 'success' : ($user->application_status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($user->application_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $users->links() }}
    </div>
</div>
@endsection
