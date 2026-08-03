<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Reset Password - HRM System</title>
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
                                        <span class="active fs-18 fw-bold">CREATE NEW PASSWORD</span>
                                    </div>
                                </div>

                                {{-- Flash Notifications --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
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
                                            Resetting password for:<br>
                                            <strong class="text-dark">{{ $email ?? session('reset_email') }}</strong>
                                        </p>

                                        <form action="{{ route('password.update') }}" id="authentication" method="post" class="sign_validator">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token ?? session('reset_token') }}">
                                            <input type="hidden" name="email" value="{{ $email ?? session('reset_email') }}">

                                            <div class="form-group mb-3">
                                                <label for="password" class="fw-semibold"> {{ __('New Password') }}</label>
                                                <input type="password"
                                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                    id="password" name="password" placeholder="Minimum 8 characters" required autofocus />
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="password_confirmation" class="fw-semibold"> {{ __('Confirm New Password') }}</label>
                                                <input type="password"
                                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                                    id="password_confirmation" name="password_confirmation" placeholder="Re-type new password" required />
                                                @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <button type="submit" class="btn btn-primary btn-block w-100 py-2 fs-16">
                                                    {{ __('Save & Reset Password') }}
                                                </button>
                                            </div>
                                        </form>

                                        <div class="text-center mt-3 pt-2 border-top">
                                            <small><a href="{{ route('login') }}" class="text-decoration-none text-muted">Back to Login</a></small>
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