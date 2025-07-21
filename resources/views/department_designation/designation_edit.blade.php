@extends('layouts.main')

@section('title')
    Edit Designation
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Designation</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('designations.index') }}">Designations</a></div>
            <div class="breadcrumb-item">Edit Designation</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Designation Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('designations.update', $designation->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name">Designation Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $designation->name }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="department_id">Department</label>
                                    <select class="form-control" id="department_id" name="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $designation->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success">Update Designation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 