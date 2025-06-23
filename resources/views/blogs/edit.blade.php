@extends('layouts.main')
@section('title', 'Blogs')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Blog</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>

    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Blog Details</h4>
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

                        <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        {{-- <label for="thumbnail">Thumbnail Image</label> --}}
                                        <input type="file" name="thumbnail" id="thumbnail" class="d-none" accept="image/*" onchange="previewThumbnail(this)">
                                        
                                        <div class="thumbnail">
                                            <div class="upload-area" onclick="document.getElementById('thumbnail').click()">
                                                <img id="thumbnail-preview-image" 
                                                    src="{{ $blog->thumbnail ? asset($blog->thumbnail) : asset('assets/img/image-.png') }}" 
                                                    alt="{{ $blog->title ?? 'Click to upload' }}" 
                                                    class="upload-image img-fluid w-100" 
                                                    style="cursor: pointer;">
                                                <div class="upload-overlay">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <p>Click to upload</p>
                                                </div>
                                            </div>
                                            <div class="mt-2 d-flex">
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="removeThumbnail()" 
                                                    style="{{ $blog->thumbnail ? '' : 'display: none;' }}" 
                                                    id="remove-thumbnail-btn">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                                <span class="ml-2"></span>
                                                <small class="form-text text-muted">800x600px (Max 2MB)</small>
                                                
                                            </div>
                                            @error('thumbnail')
                                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">Blog Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category">Category <span class="text-danger">*</span></label>
                                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $categoryName)
                                                <option value="{{ $categoryName }}" {{ old('category', $blog->category) == $categoryName ? 'selected' : '' }}>
                                                    {{ $categoryName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Blog Content <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control summernote @error('description') is-invalid @enderror" required>{{ old('description', $blog->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Update Blog
                                </button>
                                <a href="{{ route('blogs.index') }}" class="btn btn-light btn-lg ml-2">
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
<style>
    .upload-area {
        position: relative;
        display: block;
        transition: all 0.3s ease;
        border: 2px dashed #ccc;
        padding: 10px;
        border-radius: 5px;
        background: #f9f9f9;
        /* margin: 0 auto; */
        width: 200px;
    }
    .upload-area:hover {
        border-color: #6777ef;
    }
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .upload-overlay i {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .upload-overlay p {
        margin: 0;
    }
    .upload-area:hover .upload-overlay {
        opacity: 0.9;
    }
    .upload-image {
        box-shadow: none !important;
        border: none;
        background: transparent;
        display: block;
        max-width: 200px;
        height: auto;
        max-height: 200px;
    }
    .thumbnail-container {
        max-width: 100%;
        margin: 0 auto;
    }
    @media (min-width: 768px) {
        .thumbnail-container {
            max-width: 40%;
        }
    }
    @media (min-width: 992px) {
        .thumbnail-container {
            max-width: 30%;
        }
    }
</style>
<script>
    $(document).ready(function() {
        // Initialize summernote with enhanced features for blog editing
        $('.summernote').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Handle image uploads if needed
                    for(let i=0; i < files.length; i++) {
                        // You could implement AJAX image upload here
                        // This is a simple example that inserts the image directly
                        let reader = new FileReader();
                        reader.onloadend = function() {
                            let image = $('<img>').attr('src', reader.result);
                            $('.summernote').summernote('insertNode', image[0]);
                        }
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        });
        
        // Check if there's an existing image and set up the UI accordingly
        if ('{{ $blog->thumbnail }}' !== '') {
            // Make sure the remove button is visible
            $('#remove-thumbnail-btn').show();
        }
    });
    
    // Function to preview the thumbnail
    function previewThumbnail(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var image = new Image();
                image.src = e.target.result;
                
                image.onload = function() {
                    $('#thumbnail-preview-image').attr('src', e.target.result);
                    $('.upload-overlay').css('opacity', '0');
                    $('#remove-thumbnail-btn').show();
                    
                    // Add a hidden input to indicate that the thumbnail has been changed
                    if($('#thumbnail_changed').length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'thumbnail_changed',
                            name: 'thumbnail_changed',
                            value: '1'
                        }).appendTo('form');
                    }
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Function to remove the thumbnail
    function removeThumbnail() {
        $('#thumbnail').val('');
        $('#thumbnail-preview-image').attr('src', '{{ asset('assets/img/image-.png') }}');
        $('.upload-overlay').css('opacity', '0');
        $('#remove-thumbnail-btn').hide();
        
        // Add a hidden input to indicate that the thumbnail should be removed
        if($('#thumbnail_remove').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'thumbnail_remove',
                name: 'thumbnail_remove',
                value: '1'
            }).appendTo('form');
        }
    }
</script>
@endsection 