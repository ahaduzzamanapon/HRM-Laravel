@extends('layouts.default')

@section('title')
Edit PF Scheme @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit PF Scheme</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.schemes.index') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 6px 20px;">Back to Schemes</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 20px;">
                        <h4>Edit Scheme Details</h4>
                    </div>
                    
                    <form action="{{ route('pf.schemes.update', $scheme->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name" style="font-weight: bold;">Scheme Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $scheme->name) }}" required placeholder="Enter scheme name">
                            @error('name')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="employee_contribution_percentage" style="font-weight: bold;">Employee Contribution (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="employee_contribution_percentage" id="employee_contribution_percentage" class="form-control @error('employee_contribution_percentage') is-invalid @enderror" value="{{ old('employee_contribution_percentage', $scheme->employee_contribution_percentage) }}" required min="0" placeholder="E.g. 10.00">
                            @error('employee_contribution_percentage')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="employer_contribution_percentage" style="font-weight: bold;">Employer Contribution (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="employer_contribution_percentage" id="employer_contribution_percentage" class="form-control @error('employer_contribution_percentage') is-invalid @enderror" value="{{ old('employer_contribution_percentage', $scheme->employer_contribution_percentage) }}" required min="0" placeholder="E.g. 10.00">
                            @error('employer_contribution_percentage')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="border-radius: 20px; padding: 10px 30px; font-weight: bold;">Update Scheme</button>
                            <a href="{{ route('pf.schemes.index') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 10px 30px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
