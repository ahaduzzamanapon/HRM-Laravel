<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Enter OTP</title>
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
    <div class="container">
        <div class="card transparent-form">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 card-align bg-white-transparent mx-auto">
                    <div class="row">
                        <div class="col-12 signup-form">
                            <div class="card-header border-bottom-0">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h2 class="text-center">
                                            <span>{{ \App\Models\SiteSetting::first()->site_name ?? 'Your Site Name' }}</span>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-md-12 signup-header-text">
                                        <span class="active fs-18">ENTER OTP</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <form action="{{ route('password.verify.otp') }}" id="authentication" method="post" class="sign_validator">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ session('email') }}">
                                            <div class="form-group">
                                                <label for="otp"> {{ __('OTP') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-lg @error('otp') is-invalid @enderror"
                                                    id="otp" name="otp" placeholder="Enter OTP" required />
                                                @error('otp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <input type="submit" value="{{ __('Verify OTP') }}"
                                                    class="btn btn-primary btn-block" />
                                            </div>
                                        </form>
                                          <div class="text-center mt-3">
                                            <small><a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></small>
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