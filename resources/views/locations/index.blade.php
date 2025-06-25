@extends('layouts.main')
@section('title', 'Locations')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Locations</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Locations</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Locations</h4>
                        <div class="card-header-action">
                            <a href="{{ route('locations.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Location
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mt-3 mb-4">
                            <form action="{{ route('locations.index') }}" method="GET" class="row">
                                <div class="form-group col-md-3">
                                    <label for="search" class="sr-only">Search</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search" name="search" 
                                            placeholder="Search locations..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="city">City</label>
                                    <select class="form-control" id="city" name="city" onchange="this.form.submit()">
                                        <option value="">All Cities</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="state">State</label>
                                    <select class="form-control" id="state" name="state" onchange="this.form.submit()">
                                        <option value="">All States</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="country">Country</label>
                                    <select class="form-control" id="country" name="country" onchange="this.form.submit()">
                                        <option value="">All Countries</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="d-block">&nbsp;</label>
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter mr-1"></i> Filter
                                        </button>
                                        @if(request()->anyFilled(['search', 'city', 'state', 'country', 'sort', 'direction']))
                                            <a href="{{ route('locations.index') }}" class="btn btn-light">
                                                <i class="fas fa-times mr-1"></i> Clear
                                            </a>
                                        @endif
                                    </div>

                                    <div class="ml-auto mt-2">
                                        <div class="btn-group">
                                            <a href="{{ route('locations.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-amount-down mr-1"></i> Newest
                                            </a>
                                            <a href="{{ route('locations.index', ['sort' => 'name', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort') == 'name' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-alpha-down mr-1"></i> A-Z
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($locations as $location)
                                        <tr>
                                            <td>{{ $location->id }}</td>
                                            <td>{{ $location->name ?? 'N/A' }}</td>
                                            <td>{{ $location->address_line_1 }}</td>
                                            <td>{{ $location->city }}</td>
                                            <td>{{ $location->state }}</td>
                                            <td>{{ $location->country }}</td>
                                            <td>
                                                <a href="{{ route('locations.show', $location) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $location->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No locations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $locations->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            let id = $(this).data('id');
            let form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // Show success message using SweetAlert2 if exists in session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
            });
        @endif
    });
</script>
@endsection 