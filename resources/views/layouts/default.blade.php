<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $setting = DB::table('sitesettings')->first();
@endphp
@include('layouts/head')

<body>
    
<style>
.loaderss {
    --c1: #673b14;
    --c2: #0177bc;
    width: 40px;
    height: 80px;
    border-top: 4px solid var(--c1);
    border-bottom: 4px solid var(--c1);
    background: linear-gradient(90deg, var(--c1) 2px, var(--c2) 0 5px, var(--c1) 0) 50%/7px 8px no-repeat;
    display: grid;
    overflow: hidden;
    animation: l5-0 2s infinite linear;
}

.loaderss::before,
.loaderss::after {
    content: "";
    grid-area: 1/1;
    width: 75%;
    height: calc(50% - 4px);
    margin: 0 auto;
    border: 2px solid var(--c1);
    border-top: 0;
    box-sizing: content-box;
    border-radius: 0 0 40% 40%;
    -webkit-mask: linear-gradient(#000 0 0) bottom/4px 2px no-repeat,
        linear-gradient(#000 0 0);
    -webkit-mask-composite: destination-out;
    mask-composite: exclude;
    background: linear-gradient(var(--d, 0deg), var(--c2) 50%, #0000 0) bottom /100% 205%,
        linear-gradient(var(--c2) 0 0) center/0 100%;
    background-repeat: no-repeat;
    animation: inherit;
    animation-name: l5-1;
}

.loaderss::after {
    transform-origin: 50% calc(100% + 2px);
    transform: scaleY(-1);
    --s: 3px;
    --d: 180deg;
}

@keyframes l5-0 {
    80% {
        transform: rotate(0)
    }

    100% {
        transform: rotate(0.5turn)
    }
}

@keyframes l5-1 {

    10%,
    70% {
        background-size: 100% 205%, var(--s, 0) 100%
    }

    70%,
    100% {
        background-position: top, center
    }
}
</style>

<div id="loader_div"
    style="position: fixed;top: 0;left: 0;height: 100vh;width: 100vw;background-color: #ffffffa8;z-index: 99999999999;display: flex;overflow: hidden;align-items: center;justify-content: center;">
    <div class="loaderss" style="margin: auto;"></div>
</div>

    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        <div class="sidebar-header d-flex align-items-center justify-content-start"
            style="background: #0177bc;margin: 0px;margin-bottom: -1px;">
            <a href="{{ url('/') }}" class="navbar-brand" style="padding: 9.4px;">
                <div class="logo-main" style="width: 51px;">
                    @if(!empty($setting) && $setting->site_logo)
                        <img src="{{ asset($setting->site_logo) }}" alt="{{ $setting->site_name }}" height="30">
                    @else
                        LOGO
                    @endif
                </div>
                <span class="logo-title"
                    style="color: white;">{{ !empty($setting) ? $setting->site_name : 'Title' }}</span>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar" style="overflow: hidden;outline: none;padding: 0;">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    @include('layouts/sidemenu')
                </ul>
                <!-- Sidebar Menu End -->
            </div>
        </div>
        <div class="sidebar-footer"></div>
    </aside>
    <main class="main-content">
        <div class="position-relative iq-banner">
            @include('layouts/topmenu')
            <div style="margin: 8px;height: 100%;width: 98%;">
                @yield('content')
            </div>
        </div>

    </main>
    <!-- Wrapper End-->
    @include('layouts/scripts')
    @stack('scripts')
{{-- @yield('scripts_gvhgh') --}}
{{-- @yield('footer_scripts') --}}
</body>

</html>
