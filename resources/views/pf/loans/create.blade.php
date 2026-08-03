@extends('layouts.default')

@section('title')
Apply for PF Loan @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Apply for PF Loan</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
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
                        <h4>Loan Application Form</h4>
                    </div>
                    
                    <form action="{{ route('pf.loans.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="amount" style="font-weight: bold;">Loan Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required min="1000" placeholder="Enter loan amount">
                            @error('amount')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="installments" style="font-weight: bold;">Number of Installments (Months) <span class="text-danger">*</span></label>
                            <input type="number" name="installments" id="installments" class="form-control @error('installments') is-invalid @enderror" value="{{ old('installments') }}" required min="1" placeholder="Enter number of installments">
                            @error('installments')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="remarks" style="font-weight: bold;">Remarks / Reason for Loan</label>
                            <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="4" placeholder="Enter any remarks or reasons for this loan application">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="border-radius: 20px; padding: 10px 30px; font-weight: bold;">Submit Application</button>
                            <a href="{{ route('pf.dashboard') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 10px 30px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
