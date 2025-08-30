@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Dashboard Overview</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Total Students</p>
                            <h4 class="card-title">{{ number_format($stats['total_students']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Completed Profiles</p>
                            <h4 class="card-title">{{ number_format($stats['completed_profiles']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Pending Payments</p>
                            <h4 class="card-title">{{ number_format($stats['pending_payments']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-5">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Total Collected</p>
                            <h4 class="card-title">₦{{ number_format($stats['total_payments']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Application Status</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 text-center">
                        <div class="p-3 border-right">
                            <h2 class="text-success mb-0">{{ $stats['accepted_applications'] }}</h2>
                            <small class="text-muted">Accepted</small>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="p-3">
                            <h2 class="text-warning mb-0">{{ $stats['pending_applications'] }}</h2>
                            <small class="text-muted">Pending Review</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Quick Actions</div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-users me-2"></i>Manage Students
                        </a>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('admin.import.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-file-import me-2"></i>Import Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection