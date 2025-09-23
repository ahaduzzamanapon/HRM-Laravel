@extends('layouts.default')

@section('title')
Provident Fund Settings @parent
@stop

@section('content')
    <section class="content-header">
        <h1>Provident Fund Settings</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {!! Form::model($providentFundSetting, ['route' => ['providentFundSettings.update', $providentFundSetting->id], 'method' => 'patch']) !!}
                    <div class="row">
                        @include('provident_fund_settings.fields')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
