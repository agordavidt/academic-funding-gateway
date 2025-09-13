@extends('layouts.admin')

@section('title', 'Application Settings')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Set Application Deadline ⏰</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <form action="{{ route('admin.settings.deadline.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="deadline">Application Deadline</label>
                        <input type="datetime-local" name="deadline" id="deadline"
                               class="form-control @error('deadline') is-invalid @enderror"
                               value="{{ $deadline ? date('Y-m-d\TH:i', strtotime($deadline->value)) : '' }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Leave empty to remove the deadline.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Deadline</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection