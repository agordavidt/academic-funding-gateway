@extends('layouts.student')

@section('title', 'Complete Profile')

@section('content')
<div class="step-indicator">
    <div class="step completed">1</div>
    <div class="step-connector completed"></div>
    <div class="step active">2</div>
    <div class="step-connector"></div>
    <div class="step">3</div>
</div>

<h4 class="text-center mb-4">Complete Your Profile</h4>

<form method="POST" action="{{ route('student.profile.update') }}">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">First Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user text-primary"></i>
                    </span>
                    <input type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Last Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user text-primary"></i>
                    </span>
                    <input type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-phone text-primary"></i>
            </span>
            <input type="text" class="form-control" value="{{ $user->phone_number }}" disabled>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email  *</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-envelope text-primary"></i>
            </span>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" 
                   value="{{ old('email', $user->email) }}" required>
        </div>
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="school" class="form-label">School/Institution *</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-school text-primary"></i>
            </span>
            <input type="text" class="form-control @error('school') is-invalid @enderror" 
                   id="school" name="school" 
                   value="{{ old('school', $user->school) }}" required>
        </div>
        @error('school')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="matriculation_number" class="form-label">Matriculation/Student Number</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-id-card text-primary"></i>
            </span>
            <input type="text" class="form-control @error('matriculation_number') is-invalid @enderror" 
                   id="matriculation_number" name="matriculation_number" 
                   value="{{ old('matriculation_number', $user->matriculation_number) }}">
        </div>
        @error('matriculation_number')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
   

    <div class="form-group">
        <label for="need_assessment_text" class="form-label">Need Assessment *</label>
        <textarea class="form-control @error('need_assessment_text') is-invalid @enderror" 
                  id="need_assessment_text" name="need_assessment_text" rows="5" 
                  placeholder="Please describe why you need this grant and how it will help your academic/career goals (max 1000 characters)" 
                  required>{{ old('need_assessment_text', $application->need_assessment_text) }}</textarea>
        @error('need_assessment_text')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">Maximum 1000 characters</small>
    </div>

    <div class="d-grid mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-arrow-right me-2"></i>Complete Registration
        </button>
    </div>
</form>
@endsection