@extends('layouts.default')

@section('title')
Initiate PF Settlement @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Initiate PF Settlement</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.settlements.index') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 6px 20px;">Back to Settlements</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding-left:15px; margin-bottom: 20px;">
                        <h4>Settlement Details</h4>
                    </div>
                    
                    <form action="{{ route('pf.settlements.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="employee_id" style="font-weight: bold;">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->emp_id ? $employee->emp_id . ' - ' : '' }}{{ $employee->name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Select the active PF member leaving or settling their account.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="reason" style="font-weight: bold;">Reason for Settlement <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required placeholder="E.g. Resignation, Retirement, Termination">{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="border-radius: 20px; padding: 10px 30px; font-weight: bold;">Initiate Settlement</button>
                            <a href="{{ route('pf.settlements.index') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 10px 30px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
