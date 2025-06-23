@extends('layouts.main')
@section('title', 'Case Studies')

@section('content')
<style>
.gallery-thumb {
    width: 100px;
    height: 100px;
    /* object-fit: cover; */
}
</style>
<section class="section">
    <div class="section-header">
        <h1>Edit Case Study</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('case_studies.index') }}">Case Studies</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Case Study Details</h4>
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

                        <form action="{{ route('case_studies.update', $caseStudy) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $caseStudy->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $caseStudy->slug) }}">
                                        <small class="form-text text-muted">Leave empty to auto-generate from title.</small>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_name">Client Name</label>
                                        <input type="text" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name', $caseStudy->client_name) }}">
                                        @error('client_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">Category</label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id', $caseStudy->category_id) == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="active" {{ old('status', $caseStudy->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $caseStudy->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_url">Project URL</label>
                                        <input type="url" name="project_url" id="project_url" class="form-control @error('project_url') is-invalid @enderror" value="{{ old('project_url', $caseStudy->project_url) }}">
                                        @error('project_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $caseStudy->start_date ? $caseStudy->start_date->format('Y-m-d') : '') }}">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $caseStudy->end_date ? $caseStudy->end_date->format('Y-m-d') : '') }}">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order">Display Order</label>
                                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $caseStudy->order) }}" min="0">
                                        @error('order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" {{ old('is_featured', $caseStudy->is_featured) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_featured">Featured Case Study</label>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="thumbnail">Thumbnail Image</label>
                                        @if($caseStudy->thumbnail)
                                            <div class="mb-2">
                                                <img src="{{ asset($caseStudy->thumbnail) }}" alt="{{ $caseStudy->title }}" class="img-thumbnail mb-2" style="max-height: 150px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remove_thumbnail" id="remove_thumbnail" value="1">
                                                    <label class="form-check-label" for="remove_thumbnail">
                                                        Remove current thumbnail
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input @error('thumbnail') is-invalid @enderror" id="thumbnail" accept="image/*">
                                            <label class="custom-file-label" for="thumbnail">Choose new file</label>
                                            <small class="form-text text-muted">Recommended size: 800x600px (Max: 2MB)</small>
                                            @error('thumbnail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            
                            @if($caseStudy->images && count($caseStudy->images) > 0)
                                <div class="form-group">
                                    <label>Current Gallery Images</label>
                                    <div class="row">
                                        @foreach($caseStudy->images as $index => $image)
                                            <div class="col-md-2 mb-3">
                                                <div class="card">
                                                    <img src="{{ asset($image) }}" class="card-img-top gallery-thumb" alt="Gallery image">
                                                    <div class="card-body p-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="remove_images[]" id="remove_image_{{ $index }}" value="{{ $index }}">
                                                            <label class="form-check-label" for="remove_image_{{ $index }}">
                                                                Remove
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                                    <div class="form-group">
                                        <label for="images">Gallery Images</label>
                                        <div class="custom-file">
                                            <input type="file" name="images[]" class="custom-file-input @error('images.*') is-invalid @enderror" id="images" accept="image/*" multiple>
                                            <label class="custom-file-label" for="images">Choose files</label>
                                            <small class="form-text text-muted">You can select multiple images (Max: 2MB each)</small>
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                

                            <div class="form-group">
                                <label for="short_summary">Short Summary</label>
                                <textarea name="short_summary" id="short_summary" class="form-control @error('short_summary') is-invalid @enderror" rows="3">{{ old('short_summary', $caseStudy->short_summary) }}</textarea>
                                @error('short_summary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control summernote @error('description') is-invalid @enderror" required>{{ old('description', $caseStudy->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Update Case Study
                                </button>
                                <a href="{{ route('case_studies.index') }}" class="btn btn-light btn-lg ml-2">
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

@section('scripts')
<link rel="stylesheet" href="{{ asset('assets/bundles/summernote/summernote-bs4.css') }}">
<script src="{{ asset('assets/bundles/summernote/summernote-bs4.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize summernote
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Auto-generate slug from title
        $('#title').on('blur', function() {
            if ($('#slug').val() === '{{ $caseStudy->slug }}' || $('#slug').val() === '') {
                const name = $(this).val();
                const slug = name.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $('#slug').val(slug);
            }
        });
        
        // Show selected filename on custom file inputs
        $('.custom-file-input').on('change', function() {
            let fileName = '';
            if (this.files && this.files.length > 1) {
                fileName = `${this.files.length} files selected`;
            } else {
                fileName = $(this).val().split('\\').pop();
            }
            $(this).next('.custom-file-label').addClass('selected').html(fileName);
        });
        
        // Disable thumbnail input if remove checkbox is checked
        $('#remove_thumbnail').on('change', function() {
            if($(this).is(':checked')) {
                $('#thumbnail').prop('disabled', true);
            } else {
                $('#thumbnail').prop('disabled', false);
            }
        });
    });
</script>
@endsection 