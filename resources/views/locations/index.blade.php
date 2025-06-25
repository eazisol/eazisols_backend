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
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route('locations.index') }}" method="GET" class="form-inline">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search locations..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="city" class="form-control" style="min-width: 150px;">
                                            <option value="">All Cities</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="state" class="form-control" style="min-width: 150px;">
                                            <option value="">All States</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                                    {{ $state }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="country" class="form-control" style="min-width: 150px;">
                                            <option value="">All Countries</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                                    {{ $country }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary mb-2 mr-2">
                                        <i class="fas fa-filter mr-1"></i> Apply Filters
                                    </button>
                                    
                                    @if(request()->anyFilled(['search', 'city', 'state', 'country']))
                                        <a href="{{ route('locations.index') }}" class="btn btn-light mb-2">
                                            <i class="fas fa-times mr-1"></i> Clear Filters
                                        </a>
                                    @endif
                                    
                                    <div class="ml-auto mb-2">
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
                                </form>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            @if(request()->anyFilled(['search', 'city', 'state', 'country']))
                                <div class="active-filters mb-3">
                                    <span class="font-weight-bold mr-2">Active Filters:</span>
                                    @if(request('search'))
                                        <span class="badge badge-info mr-1">
                                            Search: "{{ request('search') }}"
                                        </span>
                                    @endif
                                    
                                    @if(request('city'))
                                        <span class="badge badge-info mr-1">
                                            City: {{ request('city') }}
                                        </span>
                                    @endif
                                    
                                    @if(request('state'))
                                        <span class="badge badge-info mr-1">
                                            State: {{ request('state') }}
                                        </span>
                                    @endif
                                    
                                    @if(request('country'))
                                        <span class="badge badge-info mr-1">
                                            Country: {{ request('country') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>×</span>
                                    </button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif
                        
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
                title: 'Delete Location',
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection 