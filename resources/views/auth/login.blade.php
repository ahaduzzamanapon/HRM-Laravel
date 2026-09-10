<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico')}}" />
    <!--page level css -->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">


    <link href="{{ asset('vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/auth.css')}}" rel="stylesheet">
    <link href="{{ asset('css/custom.css')}}" rel="stylesheet">
    <!--end of page level css-->

    <style>
        * {
            box-sizing: border-box;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                                        <span class="active fs-18">SIGN IN</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <form action="{{ route('login') }}" id="authentication" method="post"
                                            class="sign_validator">
                                            @csrf
                                            <div class="form-group">
                                                <label for="login">{{ __('Email / Employee ID') }}</label>
                                                <input type="text"
                                                    class="form-control form-control-lg @error('login') is-invalid @enderror @error('emp_id') is-invalid @enderror @error('email') is-invalid @enderror"
                                                    id="login" name="login" placeholder="Email or Employee ID"
                                                    value="{{ old('login') ?? old('emp_id') ?? old('email') }}" required autofocus />
                                                @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                @error('emp_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                            <div class="form-group position-relative">
                                                <label for="password">{{ __('Password') }}</label>
                                                <input type="password"
                                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                    id="password" name="password" placeholder="Password" required />
                                                <span class="fa fa-eye field-icon toggle-password" id="togglePassword"></span>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group checkbox d-none">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="remember">Remember
                                                        Me</label>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <input type="submit" value="{{ __('Sign In') }}"
                                                    class="btn btn-primary btn-block" />
                                            </div>

                                        </form>
                                        <div class="text-center">
                                            <small><a href="{{ route('password.request') }}"
                                                    class="text-decoration-none">Forgot your
                                                    password?</a></small>
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
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    </body>

</html>
