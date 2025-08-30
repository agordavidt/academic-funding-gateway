@extends('layouts.admin')

@section('title', 'Import Student Data')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">Import Student Data</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Upload CSV File</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.import.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">Select a CSV file containing student data. Maximum file size: 2MB</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload and Import</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>CSV Format Requirements</h5>
            </div>
            <div class="card-body">
                <p class="small">Your CSV file should contain the following columns:</p>
                <ul class="small">
                    <li><strong>phone_number</strong> (required)</li>
                    <li><strong>first_name</strong> (required)</li>
                    <li><strong>last_name</strong> (required)</li>
                    <li><strong>email</strong> (optional)</li>
                    <li><strong>school</strong> (optional)</li>
                    <li><strong>matriculation_number</strong> (optional)</li>
                    <li><strong>address</strong> (optional)</li>
                </ul>
                <div class="alert alert-info small mt-3">
                    <strong>Note:</strong> Phone numbers must be unique. Duplicate entries will be skipped.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection