@extends('layouts.default')

@section('title')
Career Page Settings @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Career Page Settings</h1>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('flash::message')
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.career-page.update') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="career_title">Career Page Title:</label>
                    <input type="text" name="career_title" id="career_title" class="form-control" value="{{ old('career_title', $siteSetting->career_title ?? '') }}" placeholder="e.g. Discover Your Future">
                    @error('career_title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="career_subtitle">Career Page Subtitle:</label>
                    <textarea name="career_subtitle" id="career_subtitle" class="form-control" rows="4" placeholder="e.g. Build a rewarding career with an institution committed to excellence, integrity, and growth.">{{ old('career_subtitle', $siteSetting->career_subtitle ?? '') }}</textarea>
                    @error('career_subtitle')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
