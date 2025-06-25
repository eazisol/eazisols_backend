@extends('layouts.main')
@section('title', 'Locations')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Location</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('locations.index') }}">Locations</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Location Details</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('locations.update', $location) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name (Optional)</label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $location->name) }}" placeholder="e.g. Head Office, Branch Office, etc.">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">An optional label for this location</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line_1">Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="address_line_1" id="address_line_1" class="form-control @error('address_line_1') is-invalid @enderror" value="{{ old('address_line_1', $location->address_line_1) }}" required>
                                        @error('address_line_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_line_2">Address Line 2</label>
                                        <input type="text" name="address_line_2" id="address_line_2" class="form-control @error('address_line_2') is-invalid @enderror" value="{{ old('address_line_2', $location->address_line_2) }}">
                                        @error('address_line_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="area">Area/Neighborhood</label>
                                        <input type="text" name="area" id="area" class="form-control @error('area') is-invalid @enderror" value="{{ old('area', $location->area) }}">
                                        @error('area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $location->city) }}" required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State/Province <span class="text-danger">*</span></label>
                                        <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $location->state) }}" required>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zip_code">ZIP/Postal Code</label>
                                        <input type="text" name="zip_code" id="zip_code" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code', $location->zip_code) }}">
                                        @error('zip_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                        <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $location->country) }}" required>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Update Location
                                </button>
                                <a href="{{ route('locations.index') }}" class="btn btn-light btn-lg ml-2">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 