@extends('layouts.default')

{{-- Page title --}}
@section('title')
Payroll @parent
@stop

@section('content')
    <!-- Main content -->
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>

        <div class="card" width="88vw;">
            <section class="card-header">
                <h5 class="card-title d-inline">Payroll</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('loanTypes.create') }}">Add New</a>
                </span>
            </section>
            {{-- <div class="card-body table-responsive" >
                @include('loan_types.table')
            </div> --}}
        </div>

        {{-- <div class="text-center">
            @include('adminlte-templates::common.paginate', ['records' => $loanTypes])
        </div> --}}
    </div>
@endsection
