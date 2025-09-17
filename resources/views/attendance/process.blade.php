@extends('layouts.default')

@section('title')
Attendance Process @parent
@stop

@section('content')
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -60px;
            margin-left: -60px;
            z-index: 9999;
            display: none;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <div class="loader"></div>
    <section class="content-header">
        <h1>Attendance Process</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::open(['route' => 'attendance.process.store', 'id' => 'attendance-form']) !!}
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                {!! Form::label('from_date', 'From Date:') !!}
                                <input type="text" name="from_date" class="form-control" id="from_date"
                                    value="{{ request('from_date', date('Y-m-d')) }}" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-6">
                                {!! Form::label('to_date', 'To Date:') !!}
                                <input type="text" name="to_date" class="form-control" id="to_date"
                                    value="{{ request('to_date') }}" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('branch_id', 'Branch:') !!}
                                {!! Form::select('branch_id', ['' => 'All'] + $branches->toArray(), request('branch_id'), ['class' => 'form-control', 'id' => 'branch_id'])
                                !!}
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('department_id', 'Department:') !!}
                                {!! Form::select('department_id', ['' => 'All'] + $departments->toArray(), request('department_id'), ['class' => 'form-control', 'id' => 'department_id'])
                                !!}
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('designation_id', 'Designation:') !!}
                                {!! Form::select('designation_id', ['' => 'All'] + $designations->toArray(), request('designation_id'), ['class' => 'form-control', 'id' => 'designation_id'])
                                !!}
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Process', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="user-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td><input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    @push('scripts')
        <script>
            $(function () {
                $('#from_date, #to_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                });

                $('#select-all').on('click', function () {
                    $('.user-checkbox').prop('checked', $(this).prop('checked'));
                });

                $('#branch_id, #department_id, #designation_id').on('change', function () {
                    filterUsers();
                });

                function filterUsers() {
                    var data = {
                        branch_id: $('#branch_id').val(),
                        department_id: $('#department_id').val(),
                        designation_id: $('#designation_id').val(),
                    };

                    $.ajax({
                        type: 'GET',
                        url: '{{ route("attendance.filter") }}',
                        data: data,
                        success: function (users) {
                            var tbody = $('#user-table tbody');
                            tbody.empty();
                            users.forEach(function (user) {
                                var row = '<tr>' +
                                    '<td><input type="checkbox" name="users[]" value="' + user.id + '" class="user-checkbox"></td>' +
                                    '<td>' + user.name + '</td>' +
                                    '</tr>';
                                tbody.append(row);
                            });
                        }
                    });
                }

                $('#attendance-form').on('submit', function (e) {
                    e.preventDefault();
                    $('.loader').show();
                    var formData = $(this).serialize();

                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: formData,
                        success: function (response) {
                            $('.loader').hide();
                            if(response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            $('.loader').hide();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred: ' + error,
                            });
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
