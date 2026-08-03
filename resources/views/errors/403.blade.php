@extends('layouts.default')

@section('title', 'Access Denied')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-6 text-center">
            <div class="card shadow-lg border-0">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="im im-icon-Lock" style="font-size: 80px; color: #dc3545;"></i>
                    </div>
                    <h1 class="display-4 text-danger fw-bold">403</h1>
                    <h3 class="mb-3">Access Denied</h3>
                    <p class="text-muted mb-4">
                        {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
                        <br>
                        Please contact your administrator if you believe this is an error.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="im im-icon-Home me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
