@extends('layouts.main')

@section('title')
    Add Employee
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Add Employee</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></div>
            <div class="breadcrumb-item">Add Employee</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>New Employee Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('employees.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- User Details -->
                                <div class="form-group col-md-6">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <!-- Personal Details -->
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="current_address">Current Address</label>
                                    <textarea class="form-control" id="current_address" name="current_address"></textarea>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="permanent_address">Permanent Address</label>
                                    <textarea class="form-control" id="permanent_address" name="permanent_address"></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" id="country" name="country">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Create Employee</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection