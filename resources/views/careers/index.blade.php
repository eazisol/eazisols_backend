@extends('layouts.main')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Careers</h1>
        <div class="section-header-button">
            <a href="{{ route('careers.create') }}" class="btn btn-primary">Add New</a>
        </div>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            <div class="breadcrumb-item">Careers</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">Careers</h2>
        <p class="section-lead">
            Manage all career opportunities here.
        </p>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Careers</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($careers as $career)
                                    <tr>
                                        <td>{{ $career->id }}</td>
                                        <td>{{ $career->title }}</td>
                                        <td>{{ $career->location }}</td>
                                        <td>{{ $career->type }}</td>
                                        <td>
                                            <div class="badge badge-{{ $career->status == 'active' ? 'success' : 'danger' }}">
                                                {{ $career->status }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('careers.show', $career->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('careers.edit', $career->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('careers.destroy', $career->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this career?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-1').DataTable();
    });
</script>
@endsection 