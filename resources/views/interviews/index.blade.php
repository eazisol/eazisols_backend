@extends('layouts.main')
@section('title', 'Interviews')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Interviews</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Interviews</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Interviews</h4>
                        <div class="card-header-action">
                            <a href="{{ route('interviews.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Interview
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('interviews.index') }}" method="GET" class="form-inline mb-3">
                            <div class="input-group mb-2 mr-sm-2">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <select name="interview_type" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach($interviewTypes as $type)
                                        <option value="{{ $type }}" {{ request('interview_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <select name="job_status" class="form-control">
                                    <option value="">All Statuses</option>
                                    @foreach($jobStatuses as $status)
                                        <option value="{{ $status }}" {{ request('job_status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 mr-2">
                                <i class="fas fa-filter mr-1"></i> Apply Filters
                            </button>
                            @if(request()->anyFilled(['search', 'interview_type', 'job_status']))
                                <a href="{{ route('interviews.index') }}" class="btn btn-light mb-2">
                                    <i class="fas fa-times mr-1"></i> Clear Filters
                                </a>
                            @endif
                        </form>
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        {{-- <th>Email</th> --}}
                                        <th>Phone</th>
                                        <th>Time</th>
                                        {{-- <th>Position Applied</th> --}}
                                        {{-- <th>Interview Type</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($interviews as $interview)
                                        <tr>
                                            <td>{{ $interview->name }}</td>
                                            {{-- <td>{{ $interview->email }}</td> --}}
                                            <td>{{ $interview->phone }}</td>
                                            {{-- <td>{{ $interview->date_of_interview ? $interview->date_of_interview->format('Y-m-d') : '' }}</td> --}}
                                            <td>{{ $interview->interview_time }}</td>
                                            {{-- <td>{{ $interview->position_applied }}</td> --}}
                                            {{-- <td>{{ ucfirst($interview->interview_type) }}</td> --}}

                                            <td>
                                                <a href="{{ route('interviews.print', $interview) }}" target="_blank" class="btn btn-sm btn-secondary" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <form action="{{ route('interviews.sendMail', $interview) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="subject" value="Interview Invitation - {{ $interview->position_applied }}">
                                                    <button type="submit" class="btn btn-sm btn-info" title="Send Email">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('interviews.edit', $interview) }}" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                                <form action="{{ route('interviews.destroy', $interview) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $interview->id }}"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">No interviews found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $interviews->appends(request()->query())->links() }}
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
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Delete Interview',
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>
@endsection

