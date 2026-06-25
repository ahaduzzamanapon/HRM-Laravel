@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Job Details -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="card-title">{{ $jobPost->title }}</h2>
                    <h6 class="text-muted mb-4">
                        <i class="fa fa-map-marker"></i> {{ $jobPost->location ?? 'Remote' }}
                        @if($jobPost->deadline)
                            | <i class="fa fa-calendar"></i> Apply by: {{ $jobPost->deadline->format('M d, Y') }}
                        @endif
                    </h6>
                    
                    <hr>
                    
                    <div class="mt-4">
                        <h4>Job Description</h4>
                        <div class="text-justify" style="white-space: pre-wrap;">{{ $jobPost->description }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4">Apply for this Position</h4>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open(['route' => 'careers.apply', 'files' => true]) !!}
                        {!! Form::hidden('job_post_id', $jobPost->id) !!}
                        
                        <div class="form-group mb-3">
                            {!! Form::label('first_name', 'First Name *', ['class' => 'font-weight-bold']) !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('last_name', 'Last Name *', ['class' => 'font-weight-bold']) !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('email', 'Email Address *', ['class' => 'font-weight-bold']) !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('phone', 'Phone Number', ['class' => 'font-weight-bold']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                        </div>
                        
                        <div class="form-group mb-3">
                            {!! Form::label('resume', 'Resume (PDF/DOC) *', ['class' => 'font-weight-bold']) !!}
                            {!! Form::file('resume', ['class' => 'form-control-file', 'required']) !!}
                        </div>
                        
                        <div class="form-group mb-4">
                            {!! Form::label('cover_letter', 'Cover Letter', ['class' => 'font-weight-bold']) !!}
                            {!! Form::textarea('cover_letter', null, ['class' => 'form-control', 'rows' => 4]) !!}
                        </div>
                        
                        {!! Form::submit('Submit Application', ['class' => 'btn btn-primary btn-block w-100']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
