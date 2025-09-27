<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>
        @section('title')| Josh Admin Template @show
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" />
    <link rel="stylesheet" href="{{asset('fonts/iconmind.css')}}">

    <!-- global css -->
    <link type="text/css" href="{{ asset('css/app.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css')}}">
    <style>
        #demo {
            position: relative;

            overflow: auto;
        }
    </style>
    <!-- end of global css -->

    <!-- vendors  css -->
    @yield('header_styles')
</head>

<body>
    <!-- header logo: style can be found in header-->
   
    <div class="wrapper">

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-aside">
            <!-- sidebar: style can be found in sidebar-->
            <section class="sidebar metismenu sidebar-res">
                <!-- menu -->
            </section>
            <!-- /.sidebar -->
        </aside>


        <!--            right side bar ----------->
        <aside class="right-aside">
            @yield('content')
        </aside>
    </div>
    <!-- ./wrapper -->
    <!-- Footer end -->
    <!-- SCRIPTS -->
    <!-- global js -->
    <script src="{{ asset('js/app.js')}}" type="text/javascript"></script>
    <!-- end of page level js -->
    <!-- Start of vendor js -->
    @yield('scripts')

    <script src="{{ asset('vendors/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('js/custom.js')}}"></script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-53569782-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-53569782-1');
    </script>


</body>

</html>
