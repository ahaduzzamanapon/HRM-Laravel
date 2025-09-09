@extends('layouts.default')

@section('title')
Attendance Process @parent
@stop

@section('content')
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
                                    value="{{ request('from_date') }}" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-6">
                                {!! Form::label('to_date', 'To Date:') !!}
                                <input type="text" name="to_date" class="form-control" id="to_date"
                                    value="{{ request('to_date') }}" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('branch_id', 'Branch:') !!}
                                {!! Form::select('branch_id', ['' => 'All'] + $branches->toArray(), request('branch_id'), ['class' => 'form-control', 'id' => 'branch_id']) !!}
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('department_id', 'Department:') !!}
                                {!! Form::select('department_id', ['' => 'All'] + $departments->toArray(), request('department_id'), ['class' => 'form-control', 'id' => 'department_id']) !!}
                            </div>
                            <div class="form-group col-sm-4">
                                {!! Form::label('designation_id', 'Designation:') !!}
                                {!! Form::select('designation_id', ['' => 'All'] + $designations->toArray(), request('designation_id'), ['class' => 'form-control', 'id' => 'designation_id']) !!}
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
                console.log("Attendance process script loaded.");

                $('#from_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    onSelect: function (dateText) {
                        filterAttendance();
                    }
                });

                $('#to_date').datepicker({
                    dateFormat: 'yy-mm-dd',
                    onSelect: function (dateText) {
                        filterAttendance();
                    }
                });

                $('#select-all').on('click', function () {
                    $('.user-checkbox').prop('checked', $(this).prop('checked'));
                });

                $('#branch_id, #department_id, #designation_id').on('change', function () {
                    filterAttendance();
                });

                function filterAttendance() {
                    var data = {
                        branch_id: $('#branch_id').val(),
                        department_id: $('#department_id').val(),
                        designation_id: $('#designation_id').val(),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val()
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
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                            console.error(xhr.responseText);
                            alert("An error occurred while filtering users. Please check the console for details.");
                        },
                        complete: function () {
                            console.log("AJAX request completed.");
                        }
                    });
                }
            });
        </script>
    @endpush

@endsection