@extends('layouts.main')

@section('title')
    Edit Employee
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Employee</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></div>
            <div class="breadcrumb-item">Edit Employee</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Employee Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- User Details -->
                                <div class="form-group col-md-6">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $employee->email }}" required>
                                </div>
                                {{-- <div class="form-group col-md-6">
                                    <label for="password">New Password (optional)</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div> --}}

                                <!-- Personal Details -->
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $employee->empPersonalDetail->first_name ?? '' }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $employee->empPersonalDetail->last_name ?? '' }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $employee->empPersonalDetail->phone ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="male" {{ ($employee->empPersonalDetail->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ ($employee->empPersonalDetail->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ ($employee->empPersonalDetail->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $employee->empPersonalDetail->date_of_birth ?? '' }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="current_address">Current Address</label>
                                    <textarea class="form-control" id="current_address" name="current_address">{{ $employee->empPersonalDetail->current_address ?? '' }}</textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="permanent_address">Permanent Address</label>
                                    <textarea class="form-control" id="permanent_address" name="permanent_address">{{ $employee->empPersonalDetail->permanent_address ?? '' }}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ $employee->empPersonalDetail->city ?? '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ $employee->empPersonalDetail->state ?? '' }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ $employee->empPersonalDetail->country ?? '' }}">
                                </div>
                            </div>

                            <hr>
                            <h5>Emergency Contact</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="contact_name">Contact Name</label>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" value="{{ $employee->emergencyContacts->first()->contact_name ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="relationship">Relationship</label>
                                    <input type="text" class="form-control" id="relationship" name="relationship" value="{{ $employee->emergencyContacts->first()->relationship ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $employee->emergencyContacts->first()->phone_number ?? '' }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="alternate_phone">Alternate Phone</label>
                                    <input type="text" class="form-control" id="alternate_phone" name="alternate_phone" value="{{ $employee->emergencyContacts->first()->alternate_phone ?? '' }}">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Update Employee</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection