@extends('layouts.default')

@section('title')
Site Setting @parent
@stop

@section('content')
<section class="content-header">
    <h1>Site Setting</h1>
</section>

<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Site Setting Fields -->
                <div class="col-md-12">
                    <p><b>Site Name:</b> {{ $siteSetting->site_name }}</p>
                    <p><b>Site Email:</b> {{ $siteSetting->site_email }}</p>
                    <p><b>Site Phone:</b> {{ $siteSetting->site_phone }}</p>
                    <p><b>Site Address:</b> {{ $siteSetting->site_address }}</p>
                    <p><b>Site Logo:</b>
                        @if($siteSetting->site_logo)
                            <img src="{{ asset($siteSetting->site_logo) }}" alt="Site Logo" width="100">
                        @else
                            N/A
                        @endif
                    </p>
                    <p><b>Site Favicon:</b>
                        @if($siteSetting->site_favicon)
                            <img src="{{ asset($siteSetting->site_favicon) }}" alt="Site Favicon" width="50">
                        @else
                            N/A
                        @endif
                    </p>
                    <p><b>Site Description:</b> {{ $siteSetting->site_description }}</p>
                    <p><b>Site Keywords:</b> {{ $siteSetting->site_keywords }}</p>
                    <p><b>Site Author:</b> {{ $siteSetting->site_author }}</p>
                    <p><b>Site Footer:</b> {{ $siteSetting->site_footer }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('siteSettings.index') }}" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>
@endsection