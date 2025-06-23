@extends('layouts.main')
@section('title', 'Case Studies')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Case Studies</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Case Studies</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>All Case Studies</h4>
                        <div class="card-header-action">
                            <a href="{{ route('case_studies.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Case Study
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route('case_studies.index') }}" method="GET" class="form-inline">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <input type="text" name="search" class="form-control" placeholder="Search case studies..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="category_id" class="form-control" style="min-width: 150px;">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ ucfirst($category->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="status" class="form-control" style="min-width: 150px;">
                                            <option value="">All Statuses</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group mb-2 mr-2">
                                        <select name="is_featured" class="form-control" style="min-width: 150px;">
                                            <option value="">All Featured Status</option>
                                            @foreach($featured as $key => $value)
                                                <option value="{{ $key }}" {{ request('is_featured') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary mb-2 mr-2">
                                        <i class="fas fa-filter mr-1"></i> Apply Filters
                                    </button>
                                    
                                    @if(request()->anyFilled(['search', 'category_id', 'status', 'is_featured']))
                                        <a href="{{ route('case_studies.index') }}" class="btn btn-light mb-2">
                                            <i class="fas fa-times mr-1"></i> Clear Filters
                                        </a>
                                    @endif
                                    
                                    <div class="ml-auto mb-2">
                                        <div class="btn-group">
                                            <a href="{{ route('case_studies.index', ['sort' => 'created_at', 'direction' => 'desc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort', 'created_at') == 'created_at' && request('direction', 'desc') == 'desc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-amount-down mr-1"></i> Newest
                                            </a>
                                            <a href="{{ route('case_studies.index', ['sort' => 'title', 'direction' => 'asc'] + request()->except(['sort', 'direction'])) }}" 
                                               class="btn {{ request('sort') == 'title' && request('direction') == 'asc' ? 'btn-primary' : 'btn-outline-primary' }}">
                                                <i class="fas fa-sort-alpha-down mr-1"></i> A-Z
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="px-2">
                            @if(request()->anyFilled(['search', 'category_id', 'status', 'is_featured']))
                                <div class="active-filters mb-3">
                                    <span class="font-weight-bold mr-2">Active Filters:</span>
                                    @if(request('search'))
                                        <span class="badge badge-info mr-1">
                                            Search: "{{ request('search') }}"
                                        </span>
                                    @endif
                                    
                                    @if(request('category_id'))
                                        @php
                                            $selectedCategory = $categories->where('id', request('category_id'))->first();
                                        @endphp
                                        <span class="badge badge-info mr-1">
                                            Category: {{ $selectedCategory ? ucfirst($selectedCategory->name) : '' }}
                                        </span>
                                    @endif
                                    
                                    @if(request('status'))
                                        <span class="badge badge-info mr-1">
                                            Status: {{ ucfirst(request('status')) }}
                                        </span>
                                    @endif
                                    
                                    @if(request('is_featured') !== null && request('is_featured') !== '')
                                        <span class="badge badge-info mr-1">
                                            Featured: {{ $featured[request('is_featured')] }}
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
                                        {{-- <th>ID</th> --}}
                                        <th>Thumbnail</th>
                                        <th>Title</th>
                                        <th>Client</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($caseStudies as $caseStudy)
                                        <tr>
                                            {{-- <td>{{ $caseStudy->id }}</td> --}}
                                            <td>
                                                @if($caseStudy->thumbnail)
                                                    <img src="{{ asset($caseStudy->thumbnail) }}" alt="{{ $caseStudy->title }}" width="50" class="img-thumbnail">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $caseStudy->title }}</td>
                                            <td>{{ $caseStudy->client_name ?? 'N/A' }}</td>
                                            <td>
                                                @if($caseStudy->category)
                                                    {{ $caseStudy->category }}
                                                @elseif($caseStudy->category_id && $caseStudy->category())
                                                    {{ $caseStudy->category->name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $caseStudy->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($caseStudy->status) }}
                                                </span>
                                            </td>
                                            
                                            <td>{{ $caseStudy->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('case_studies.edit', $caseStudy) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('case_studies.destroy', $caseStudy) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $caseStudy->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No case studies found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $caseStudies->appends(request()->query())->links() }}
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
                title: 'Delete Case Study',
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });
    });
</script>
@endsection 