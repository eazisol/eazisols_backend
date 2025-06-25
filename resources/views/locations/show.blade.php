@extends('layouts.main')
@section('title', 'Locations')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Location Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('locations.index') }}">Locations</a></div>
            <div class="breadcrumb-item">{{ $location->name ?? 'Location Details' }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $location->name ?? 'Location #' . $location->id }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('locations.edit', $location) }}" class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="30%">ID</th>
                                        <td>{{ $location->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $location->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address Line 1</th>
                                        <td>{{ $location->address_line_1 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address Line 2</th>
                                        <td>{{ $location->address_line_2 ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Area</th>
                                        <td>{{ $location->area ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td>{{ $location->city }}</td>
                                    </tr>
                                    <tr>
                                        <th>State/Province</th>
                                        <td>{{ $location->state }}</td>
                                    </tr>
                                    <tr>
                                        <th>Zip/Postal Code</th>
                                        <td>{{ $location->zip_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $location->country }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $location->created_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $location->updated_at->format('F d, Y h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">
                                        <h4>Full Address</h4>
                                    </div>
                                    <div class="card-body">
                                        <address>
                                            @if($location->name)
                                                <strong>{{ $location->name }}</strong><br>
                                            @endif
                                            {{ $location->address_line_1 }}<br>
                                            @if($location->address_line_2)
                                                {{ $location->address_line_2 }}<br>
                                            @endif
                                            @if($location->area)
                                                {{ $location->area }}<br>
                                            @endif
                                            {{ $location->city }}, {{ $location->state }} {{ $location->zip_code ?? '' }}<br>
                                            {{ $location->country }}
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Locations
                        </a>
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
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>
@endsection 