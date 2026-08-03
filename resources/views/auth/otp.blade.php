<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Enter OTP - HRM System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico')}}" />
    <!--page level css -->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/auth.css')}}" rel="stylesheet">
    <link href="{{ asset('css/custom.css')}}" rel="stylesheet">
    <!--end of page level css-->
</head>
<body id="sign-up" style="background-image: url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; min-height: 100vh;">
    <div class="container py-5">
        <div class="card transparent-form">
            <div class="row">
                <div class="col-lg-5 col-md-7 col-12 card-align bg-white-transparent mx-auto">
                    <div class="row">
                        <div class="col-12 signup-form">
                            <div class="card-header border-bottom-0 text-center">
                                <h2>
                                    <span>{{ \App\Models\SiteSetting::first()->site_name ?? 'Banking HRM System' }}</span>
                                </h2>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row mb-3">
                                    <div class="col-md-12 signup-header-text text-center">
                                        <span class="active fs-18 fw-bold">ENTER VERIFICATION OTP</span>
                                    </div>
                                </div>

                                {{-- Flash Notifications --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('warning'))
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size:13px;">
                                        <i class="fa fa-exclamation-triangle me-1"></i> {{ session('warning') }}
                                    </div>
                                @endif

                                @if (session('status'))
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-muted text-center mb-3" style="font-size:14px;">
                                            Enter the 6-digit code sent to:<br>
                                            <strong class="text-dark">{{ $email ?? session('reset_email') }}</strong>
                                        </p>

                                        <form action="{{ route('password.verify.otp') }}" id="authentication" method="post" class="sign_validator">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $email ?? session('reset_email') }}">
                                            
                                            <div class="form-group mb-3">
                                                <label for="otp" class="fw-semibold"> {{ __('Verification OTP Code') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-lg text-center fw-bold @error('otp') is-invalid @enderror"
                                                    style="letter-spacing: 6px; font-size: 24px;"
                                                    id="otp" name="otp" placeholder="Enter 6-digit OTP" 
                                                    maxlength="6" autocomplete="off" required autofocus />
                                                @error('otp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <button type="submit" class="btn btn-primary btn-block w-100 py-2 fs-16">
                                                    {{ __('Verify OTP & Continue') }}
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Resend OTP Option --}}
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                            <form action="{{ route('password.resend.otp') }}" method="post" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ $email ?? session('reset_email') }}">
                                                <button type="submit" class="btn btn-link p-0 text-decoration-none" style="font-size:13px;">
                                                    <i class="fa fa-refresh me-1"></i> Resend OTP Code
                                                </button>
                                            </form>
                                            <a href="{{ route('login') }}" class="text-decoration-none text-muted" style="font-size:13px;">
                                                Back to Login
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>