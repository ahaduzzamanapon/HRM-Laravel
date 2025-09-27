@extends('layouts.default')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Allowance Settings</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('allowanceSettings.create') }}">
                        Add New
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="allowanceSettings-table">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Tax Free Limit</th>
                            <th>City Specific</th>
                            <th>City Value</th>
                            <th>Is Active</th>
                            <th colspan="3">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($allowanceSettings as $key => $allowanceSetting)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $allowanceSetting->name }}</td>
                                <td>{{ $allowanceSetting->type }}</td>
                                <td>{{ $allowanceSetting->value }}</td>
                                <td>{{ $allowanceSetting->tax_free_limit }}</td>
                                <td>{{ $allowanceSetting->city_specific ? 'Yes' : 'No' }}</td>
                                <td>{{ $allowanceSetting->city_value }}</td>
                                <td>{{ $allowanceSetting->is_active ? 'Yes' : 'No' }}</td>
                                <td width="120">
                                    {!! Form::open(['route' => ['allowanceSettings.destroy', $allowanceSetting->id], 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('allowanceSettings.show', [$allowanceSetting->id]) }}"
                                               class='dropdown-item'>
                                                <i class="far fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('allowanceSettings.edit', [$allowanceSetting->id]) }}"
                                               class='dropdown-item'>
                                                <i class="far fa-edit"></i> Edit
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            {!! Form::button('<i class="far fa-trash-alt"></i> Delete', ['type' => 'submit', 'class' => 'dropdown-item text-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer clearfix">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
