@extends('layouts.admin')

@section('title', 'Add & Import Student Data')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Add & Import Student Data</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Message Display Section --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('import_errors') && count(session('import_errors')) > 0)
    <div class="alert alert-warning">
        <h5>Import Errors:</h5>
        <ul>
            @foreach (session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    {{-- Manual Creation Form --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Manually Add a Student</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.import.create') }}">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="e.g., 08012345678" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email (Optional)</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="school">School (Optional)</label>
                        <input type="text" class="form-control" id="school" name="school">
                    </div>
                    <div class="form-group">
                        <label for="matriculation_number">Matriculation Number (Optional)</label>
                        <input type="text" class="form-control" id="matriculation_number" name="matriculation_number">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Create Student Record
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- File Upload Form --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Upload Student Data File</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.import.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file" class="form-label">Data File</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file" name="file" accept=".csv,.txt,.xlsx,.xls" required>
                            <label class="custom-file-label" for="file">Choose file</label>
                        </div>
                        <small class="form-text text-muted">
                            Select a CSV or Excel file containing student data. 
                            <br>Supported formats: CSV (.csv), Excel (.xlsx, .xls)
                            <br>Maximum file size: 5MB
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-upload me-2"></i>Upload and Import
                    </button>
                </form>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">File Format Requirements</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Your file should contain the following columns:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>phone_number</strong></span>
                                <span class="badge badge-primary badge-pill">Required</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>first_name</strong></span>
                                <span class="badge badge-primary badge-pill">Required</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>last_name</strong></span>
                                <span class="badge badge-primary badge-pill">Required</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>email</strong></span>
                                <span class="badge badge-secondary badge-pill">Optional</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>school</strong></span>
                                <span class="badge badge-secondary badge-pill">Optional</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>matriculation_number</strong></span>
                                <span class="badge badge-secondary badge-pill">Optional</span>
                            </li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Notes:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Phone numbers must be unique.</li>
                                <li>Column names are case-insensitive.</li>
                                <li>Both CSV and Excel formats are supported.</li>
                                <li>Empty rows will be skipped.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const fileLabel = document.querySelector('.custom-file-label');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                fileLabel.textContent = `${fileName} (${fileSize} MB)`;
            } else {
                fileLabel.textContent = 'Choose file';
            }
        });
    });
</script>
@endpush