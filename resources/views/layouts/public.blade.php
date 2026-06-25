<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Corporate Careers')</title>
    <!-- Bootstrap CSS for modern styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
    @php
        $siteSetting = \App\Models\SiteSetting::first();
        $siteName = $siteSetting->site_name ?? 'Corporate Careers';
        $siteLogo = $siteSetting->site_logo ? asset($siteSetting->site_logo) : null;
    @endphp
    <style>
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }
        .navbar {
            border-bottom: 2px solid #003366;
        }
        .navbar-brand {
            font-weight: 700;
            color: #003366 !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .footer {
            background: #003366;
            color: #ffffff;
            padding: 40px 0;
            margin-top: 60px;
        }
        .footer p {
            opacity: 0.8;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3" style="z-index: 1030;">
        <div class="container d-flex justify-content-center justify-content-lg-start">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/careers') }}">
                @if($siteLogo)
                    <img src="{{ $siteLogo }}" alt="Logo" style="height: 40px; margin-right: 15px; object-fit: contain;">
                @else
                    <i class="fa fa-university mr-2"></i> 
                @endif
                {{ $siteName }}
            </a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer text-center">
        <div class="container">
            <h5 class="mb-3 font-weight-bold" style="font-family: 'Playfair Display', serif;">Join Our Institution</h5>
            <p class="mb-0">&copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
