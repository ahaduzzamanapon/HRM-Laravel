@extends('layouts.default')

@section('title')
Provident Funds @parent
@stop

@section('content')
    <section class="content-header">
        <h1>Provident Funds</h1>
    </section>
    <div class="content">
        <div class="card">
            <div class="card-body">
                @include('provident_funds.table')
            </div>
        </div>
        <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $users])
        </div>
    </div>
@endsection
