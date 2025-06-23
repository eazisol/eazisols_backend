@extends('layouts.main')
@section('title', 'Case Studies')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Case Study Details</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('case_studies.index') }}">Case Studies</a></div>
            <div class="breadcrumb-item">{{ $caseStudy->title }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $caseStudy->title }}</h4>
                        <div class="card-header-action">
                            <a href="{{ route('case_studies.edit', $caseStudy) }}" class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <form action="{{ route('case_studies.destroy', $caseStudy) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger delete-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <table class="table table-striped">
                                    <tr>
                                        <th style="width: 200px;">Title</th>
                                        <td>{{ $caseStudy->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td>{{ $caseStudy->slug }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client</th>
                                        <td>{{ $caseStudy->client_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>
                                            @if($caseStudy->category_id && $caseStudy->category)
                                                {{ $caseStudy->category->name }}
                                            @elseif($caseStudy->category)
                                                {{ $caseStudy->category }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Project URL</th>
                                        <td>
                                            @if($caseStudy->project_url)
                                                <a href="{{ $caseStudy->project_url }}" target="_blank">{{ $caseStudy->project_url }} <i class="fas fa-external-link-alt"></i></a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge badge-{{ $caseStudy->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($caseStudy->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Featured</th>
                                        <td>{{ $caseStudy->is_featured ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time Period</th>
                                        <td>
                                            @if($caseStudy->start_date)
                                                {{ $caseStudy->start_date->format('M d, Y') }}
                                                @if($caseStudy->end_date)
                                                    to {{ $caseStudy->end_date->format('M d, Y') }}
                                                @else
                                                    to Present
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Display Order</th>
                                        <td>{{ $caseStudy->order }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $caseStudy->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $caseStudy->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                @if($caseStudy->thumbnail)
                                    <div class="mb-4">
                                        <h6 class="mb-2">Thumbnail</h6>
                                        <img src="{{ asset($caseStudy->thumbnail) }}" alt="{{ $caseStudy->title }}" class="img-fluid rounded">
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section-title mt-0">Short Summary</div>
                                <p>{{ $caseStudy->short_summary ?? 'No summary available.' }}</p>
                                
                                <div class="section-title">Description</div>
                                <div class="case-study-description">
                                    {!! $caseStudy->description !!}
                                </div>
                            </div>
                        </div>
                        
                        @if($caseStudy->images && count($caseStudy->images) > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="section-title">Gallery</div>
                                <div class="gallery gallery-md">
                                    @foreach($caseStudy->images as $image)
                                        <div class="gallery-item" data-image="{{ asset($image) }}" data-title="{{ $caseStudy->title }} - Image"></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('case_studies.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Back to Case Studies
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
        $('.delete-btn').on('click', function() {
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