@extends('layouts.default')

@section('title')
Create Job Post @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1>Create Job Post</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('adminlte-templates::common.errors')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('recruitments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="title">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="location">Location</label>
                        <select name="location" class="form-control">
                            <option value="">Select Location</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch }}" {{ old('location') == $branch ? 'selected' : '' }}>{{ $branch }}</option>
                            @endforeach
                            <option value="Remote" {{ old('location') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="employment_type">Employment Type</label>
                        <select name="employment_type" class="form-control">
                            <option value="Full-time" {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ old('employment_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Freelance" {{ old('employment_type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="Internship" {{ old('employment_type') == 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="experience_level">Experience Level</label>
                        <input type="text" name="experience_level" class="form-control" value="{{ old('experience_level') }}" placeholder="e.g. Entry Level, Mid, Senior">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="salary_range_start">Salary Start</label>
                        <input type="number" step="0.01" name="salary_range_start" class="form-control" value="{{ old('salary_range_start') }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="salary_range_end">Salary End</label>
                        <input type="number" step="0.01" name="salary_range_end" class="form-control" value="{{ old('salary_range_end') }}">
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="deadline">Deadline</label>
                        <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                    </div>

                    <div class="form-group col-sm-12">
                        <label for="description">Job Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description_editor" class="form-control" rows="8" required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="card-footer px-0 pb-0 bg-white text-right">
                    <button type="submit" class="btn btn-primary">Save Job Post</button>
                    <a href="{{ route('recruitments.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('description_editor', {
            versionCheck: false,
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token() ]) }}",
            filebrowserUploadMethod: 'form'
        });
    });
</script>
@endpush
