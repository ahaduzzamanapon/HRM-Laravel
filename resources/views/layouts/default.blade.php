<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $setting = DB::table('sitesettings')->first();
@endphp
@include('layouts/head')

<body>
    {{-- Sidebar Start (Left) --}}
    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        {{-- left side top --}}
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
                <span class="logo-title" style="color: white;">{{ !empty($setting) ? $setting->site_name : 'Title' }}</span>
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

        {{-- left side menubar --}}
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

    {{-- Main body content (right) --}}
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
