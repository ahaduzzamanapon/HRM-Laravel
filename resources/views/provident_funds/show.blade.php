@extends('layouts.default')

@section('title')
Provident Fund Statement @parent
@stop

@section('content')
    <section class="content-header">
        <h1>Provident Fund Statement for {{ $user->name }}</h1>
    </section>
    <div class="content">
        <div class="card">
            <div class="card-body">
                @include('provident_funds.show_fields')
            </div>
        </div>
        <a href="{{ route('providentFunds.index') }}" class="btn btn-default">Back</a>
    </div>
@endsection
