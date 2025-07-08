@extends('layouts.main')
@section('title', 'Careers')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Job</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('careers.index') }}">Careers</a></div>
            <div class="breadcrumb-item">Create</div>
        </div>
    </div>

    <div class="section-body">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Job Details</h4>
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

                        <form action="{{ route('careers.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Job Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                   <div class="form-group">
                                       <label for="department">Department</label>
                                       <select name="department" id="department" class="form-control @error('department') is-invalid @enderror">
                                           <option value="">-- Select Department --</option>
                                           <option value="Software Development / Engineering" {{ old('department') == 'Software Development / Engineering' ? 'selected' : '' }}>Software Development / Engineering</option>
                                           <option value="Quality Assurance (QA)" {{ old('department') == 'Quality Assurance (QA)' ? 'selected' : '' }}>Quality Assurance (QA)</option>
                                           <option value="UI/UX Design" {{ old('department') == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                                           <option value="Project Management" {{ old('department') == 'Project Management' ? 'selected' : '' }}>Project Management</option>
                                           <option value="IT / Infrastructure" {{ old('department') == 'IT / Infrastructure' ? 'selected' : '' }}>IT / Infrastructure</option>
                                           <option value="Human Resources (HR)" {{ old('department') == 'Human Resources (HR)' ? 'selected' : '' }}>Human Resources (HR)</option>
                                           <option value="Finance & Accounts" {{ old('department') == 'Finance & Accounts' ? 'selected' : '' }}>Finance & Accounts</option>
                                           <option value="Sales & Marketing" {{ old('department') == 'Sales & Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
                                           <option value="Customer Support / Client Services" {{ old('department') == 'Customer Support / Client Services' ? 'selected' : '' }}>Customer Support / Client Services</option>
                                       </select>
                                       @error('department')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                   </div>
                               </div>
                           </div>

                           <div class="row">
                               <div class="col-md-4">
                                   <div class="form-group">
                                       <label for="work_type">Work Type <span class="text-danger">*</span></label>
                                       <select name="work_type" id="work_type" class="form-control @error('work_type') is-invalid @enderror" required>
                                           <option value="">Select Type</option>
                                           <option value="Full-time" {{ old('work_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                           <option value="Part-time" {{ old('work_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                           <option value="Contract" {{ old('work_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                           <option value="Temporary" {{ old('work_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                           <option value="Internship" {{ old('work_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                       </select>
                                       @error('work_type')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                   </div>
                               </div>
                               
                               <div class="col-md-4">
                                   <div class="form-group">
                                       <label for="workplace_type">Workplace Type</label>
                                       <select name="workplace_type" id="workplace_type" class="form-control @error('workplace_type') is-invalid @enderror">
                                           <option value="">-- Select Workplace Type --</option>
                                           <option value="remote" {{ old('workplace_type') == 'remote' ? 'selected' : '' }}>Remote</option>
                                           <option value="on-site" {{ old('workplace_type') == 'on-site' ? 'selected' : '' }}>On-site</option>
                                           <option value="both" {{ old('workplace_type') == 'both' ? 'selected' : '' }}>Both</option>
                                       </select>
                                       @error('workplace_type')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                   </div>
                               </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $categoryName)
                                                <option value="{{ $categoryName }}" {{ old('category') == $categoryName ? 'selected' : '' }}>
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
                                        <label for="location">Location <span class="text-danger">*</span></label>
                                        <select name="location" id="location" class="form-control @error('location') is-invalid @enderror" required>
                                            <option value="">-- Select Location --</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->address_line_1 }}, {{ $location->city }}" {{ old('location') == $location->address_line_1.', '.$location->city ? 'selected' : '' }}>
                                                    {{ $location->address_line_1 }}, {{ $location->city }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="salary_range">Salary Range</label>
                                        <input type="text" name="salary_range" id="salary_range" class="form-control @error('salary_range') is-invalid @enderror" value="{{ old('salary_range') }}" placeholder="PKR">
                                        @error('salary_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="experience_level">Experience Level</label>
                                        <select name="experience_level" id="experience_level" class="form-control @error('experience_level') is-invalid @enderror">
                                            <option value="">Select Experience Level</option>
                                            <option value="Entry Level" {{ old('experience_level') == 'Entry Level' ? 'selected' : '' }}>Entry Level</option>
                                            <option value="Junior" {{ old('experience_level') == 'Junior' ? 'selected' : '' }}>Junior</option>
                                            <option value="Mid Level" {{ old('experience_level') == 'Mid Level' ? 'selected' : '' }}>Mid Level</option>
                                            <option value="Senior" {{ old('experience_level') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                            <option value="Lead" {{ old('experience_level') == 'Lead' ? 'selected' : '' }}>Lead</option>
                                            <option value="Manager" {{ old('experience_level') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="Executive" {{ old('experience_level') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                        </select>
                                        @error('experience_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="education">Education Requirements</label>
                                        <input type="text" name="education" id="education" class="form-control @error('education') is-invalid @enderror" value="{{ old('education') }}" placeholder="e.g. Bachelor's degree in Computer Science">
                                        @error('education')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="application_deadline">Application Deadline</label>
                                        <input type="date" name="application_deadline" id="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline') }}">
                                        @error('application_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="filled" {{ old('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="vacancy_count">Number of Vacancies</label>
                                        <input type="number" name="vacancy_count" id="vacancy_count" class="form-control @error('vacancy_count') is-invalid @enderror" value="{{ old('vacancy_count', 1) }}" min="1">
                                        @error('vacancy_count')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                

                            </div>

                            <div class="form-group">
                                <label for="description">Job Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control summernote @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="responsibilities">Responsibilities</label>
                                <textarea name="responsibilities" id="responsibilities" class="form-control summernote @error('responsibilities') is-invalid @enderror">{{ old('responsibilities') }}</textarea>
                                @error('responsibilities')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="requirements">Requirements <span class="text-danger">*</span></label>
                                <textarea name="requirements" id="requirements" class="form-control summernote @error('requirements') is-invalid @enderror" required>{{ old('requirements') }}</textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="benefits">Benefits</label>
                                <textarea name="benefits" id="benefits" class="form-control summernote @error('benefits') is-invalid @enderror">{{ old('benefits') }}</textarea>
                                @error('benefits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            {{-- SEO section removed --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Create Job
                                </button>
                                <a href="{{ route('careers.index') }}" class="btn btn-light btn-lg ml-2">
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
        // Initialize summernote with enhanced features for rich text editing
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
                    // Handle image uploads
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
        
        // Toggle SEO panel
        $('[data-collapse]').on('click', function(e) {
            e.preventDefault();
            const target = $(this).data('collapse');
            $(target).collapse('toggle');
        });
    });
</script>
@endsection