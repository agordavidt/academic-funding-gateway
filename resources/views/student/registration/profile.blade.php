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
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" 
                       value="{{ $user->first_name }}" disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" 
                       value="{{ $user->last_name }}" disabled>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" 
               value="{{ $user->phone_number }}" disabled>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email Address *</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" 
               value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="school" class="form-label">School/Institution *</label>
        <input type="text" class="form-control @error('school') is-invalid @enderror" 
               id="school" name="school" 
               value="{{ old('school', $user->school) }}" required>
        @error('school')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="matriculation_number" class="form-label">Matriculation/Student Number</label>
        <input type="text" class="form-control @error('matriculation_number') is-invalid @enderror" 
               id="matriculation_number" name="matriculation_number" 
               value="{{ old('matriculation_number', $user->matriculation_number) }}">
        @error('matriculation_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address *</label>
        <textarea class="form-control @error('address') is-invalid @enderror" 
                  id="address" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="need_assessment_text" class="form-label">Need Assessment *</label>
        <textarea class="form-control @error('need_assessment_text') is-invalid @enderror" 
                  id="need_assessment_text" name="need_assessment_text" rows="5" 
                  placeholder="Please describe why you need this grant and how it will help your academic/career goals (max 1000 characters)" 
                  required>{{ old('need_assessment_text', $application->need_assessment_text) }}</textarea>
        @error('need_assessment_text')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Maximum 1000 characters</div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-right me-2"></i>Continue to Payment
        </button>
    </div>
</form>
@endsection