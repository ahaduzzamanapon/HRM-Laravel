<div class="col-md-6">
    <!-- Site Name Field -->
    <div class="form-group">
        {!! Form::label('site_name', 'Site Name:') !!}
        {!! Form::text('site_name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Site Email Field -->
    <div class="form-group">
        {!! Form::label('site_email', 'Site Email:') !!}
        {!! Form::email('site_email', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Site Phone Field -->
    <div class="form-group">
        {!! Form::label('site_phone', 'Site Phone:') !!}
        {!! Form::text('site_phone', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Site Address Field -->
    <div class="form-group">
        {!! Form::label('site_address', 'Site Address:') !!}
        {!! Form::textarea('site_address', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>

    <!-- Site Logo Field -->
    <div class="form-group">
        {!! Form::label('site_logo', 'Site Logo:') !!}
        {!! Form::file('site_logo', ['class' => 'form-control']) !!}
        @if(isset($siteSetting) && $siteSetting->site_logo)
            <img src="{{ asset($siteSetting->site_logo) }}" alt="Site Logo" width="100" class="mt-2">
        @endif
    </div>
</div>

<div class="col-md-6">
    <!-- Site Favicon Field -->
    <div class="form-group">
        {!! Form::label('site_favicon', 'Site Favicon:') !!}
        {!! Form::file('site_favicon', ['class' => 'form-control']) !!}
        @if(isset($siteSetting) && $siteSetting->site_favicon)
            <img src="{{ asset($siteSetting->site_favicon) }}" alt="Site Favicon" width="50" class="mt-2">
        @endif
    </div>

    <!-- Site Description Field -->
    <div class="form-group">
        {!! Form::label('site_description', 'Site Description:') !!}
        {!! Form::textarea('site_description', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>

    <!-- Site Keywords Field -->
    <div class="form-group">
        {!! Form::label('site_keywords', 'Site Keywords:') !!}
        {!! Form::text('site_keywords', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Site Author Field -->
    <div class="form-group">
        {!! Form::label('site_author', 'Site Author:') !!}
        {!! Form::text('site_author', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Site Footer Field -->
    <div class="form-group">
        {!! Form::label('site_footer', 'Site Footer:') !!}
        {!! Form::text('site_footer', null, ['class' => 'form-control']) !!}
    </div>
</div>