@extends('layouts.admin')

@section('title', 'Import Student Data')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Import Student Data</div>
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
                    <div class="card-title">Upload CSV or Excel File</div>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload and Import
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">File Format Requirements</div>
                </div>
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
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong>address</strong></span>
                        <span class="badge badge-secondary badge-pill">Optional</span>
                    </li>
                </ul>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Notes:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Phone numbers must be unique</li>
                        <li>Column names are case-insensitive</li>
                        <li>Both CSV and Excel formats supported</li>
                        <li>Empty rows will be skipped</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Sample Format</div>
                </div>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Example CSV/Excel structure:</strong></p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>phone_number</th>
                                <th>first_name</th>
                                <th>last_name</th>
                                <th>email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>08012345678</td>
                                <td>John</td>
                                <td>Doe</td>
                                <td>john@email.com</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update file input label with selected file name
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        const fileLabel = document.querySelector('.custom-file-label');
        
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2); // Size in MB
                fileLabel.textContent = `${fileName} (${fileSize} MB)`;
            } else {
                fileLabel.textContent = 'Choose file';
            }
        });
    });
</script>
@endpush