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
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .nav_t {
            background: #c7e6f8;
            padding: 11px;
        }

        .nav-tabs .nav-link {

            font-weight: 700;
        }
    </style>
    <div class="loader"></div>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1>Attendance Process</h1>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                {!! Form::label('from_date', 'From Date:') !!}
                                <input type="date" name="from_date" class="form-control" id="from_date"
                                    value="{{ request('from_date', date('Y-m-d')) }}" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-6">
                                {!! Form::label('to_date', 'To Date:') !!}
                                <input type="date" name="to_date" class="form-control" id="to_date"
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
                            <button class="btn btn-primary" id="process-attendance-btn">Process</button>
                            <button class="btn btn-success" id="process-date-range-btn">Process Date Range</button>
                            <button class="btn btn-secondary" id="manual-attendance-btn">Manual Attendance</button>
                        </div>
                    </div>
                </div>
                <div class="card" id="manual-attendance-card" style="display: none;">
                    <div class="card-body">
                        <h3>Manual Attendance</h3>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                {!! Form::label('manual_clock_in', 'Clock In:') !!}
                                <input type="time" name="manual_clock_in" class="form-control" id="manual_clock_in">
                            </div>
                            <div class="form-group col-sm-6">
                                {!! Form::label('manual_clock_out', 'Clock Out:') !!}
                                <input type="time" name="manual_clock_out" class="form-control" id="manual_clock_out">
                            </div>
                        </div>
                        <button class="btn btn-primary" id="save-manual-attendance-btn">Save</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav_t" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily"
                                    type="button" role="tab" aria-controls="daily" aria-selected="true">Daily Report</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other"
                                    type="button" role="tab" aria-controls="other"
                                    aria-selected="false">Others Report</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="daily" role="tabpanel" aria-labelledby="daily-tab">
                                <div class="my-3">
                                    <button class="btn btn-sm btn-primary filter-btn" data-filter="all">
                                        <i class="fa fa-list"></i> All
                                    </button>
                                    <button class="btn btn-sm btn-success filter-btn" data-filter="present">
                                        <i class="fa fa-check"></i> Present
                                    </button>
                                    <button class="btn btn-sm btn-danger filter-btn" data-filter="absent">
                                        <i class="fa fa-times"></i> Absent
                                    </button>
                                    <button class="btn btn-sm btn-warning filter-btn" data-filter="late">
                                        <i class="fa fa-clock-o"></i> Late
                                    </button>
                                    <button class="btn btn-sm btn-primary filter-btn" data-filter="leave">
                                        <i class="fa fa-sign-out"></i> Leave
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                                <div class="my-3">
                                    <button class="btn btn-sm btn-primary filter-btn" data-filter="job_card">Emp. Job Card</button>
                                    <button class="btn btn-sm btn-primary filter-btn" data-filter="gen_report">General Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="height: 89vh; overflow-y: scroll;">
                        <table class="table table-bordered" id="user-table">
                            <thead >
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
    </div>
    @push('scripts')
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script>
            $(function () {
                // $('#from_date, #to_date').datepicker({
                //     dateFormat: 'yy-mm-dd',
                // });

                $('#manual-attendance-btn').on('click', function() {
                    $('#manual-attendance-card').toggle();
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

                $('#process-attendance-btn').on('click', function (e) {
                    e.preventDefault();
                    $('.loader').show();

                    var userIds = [];
                    $('.user-checkbox:checked').each(function () {
                        userIds.push($(this).val());
                    });

                    var data = {
                        from_date: $('#from_date').val(),
                        users: userIds,
                        _token: '{{ csrf_token() }}'
                    };

                    $.ajax({
                        type: 'POST',
                        url: '{{ route("attendance.process.store") }}',
                        data: data,
                        success: function (response) {
                            $('.loader').hide();
                            if (response.success) {
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

                $('#process-date-range-btn').on('click', function (e) {
                    e.preventDefault();
                    $('.loader').show();

                    var userIds = [];
                    $('.user-checkbox:checked').each(function () {
                        userIds.push($(this).val());
                    });

                    var fromDate = new Date($('#from_date').val());
                    var toDate = new Date($('#to_date').val());

                    var dates = [];
                    var currentDate = fromDate;
                    while (currentDate <= toDate) {
                        dates.push(new Date(currentDate));
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    function processDate(index) {
                        if (index >= dates.length) {
                            $('.loader').hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Attendance processed successfully for the selected date range.',
                            });
                            return;
                        }

                        var data = {
                            from_date: dates[index].toISOString().slice(0, 10),
                            users: userIds,
                            _token: '{{ csrf_token() }}'
                        };

                        $.ajax({
                            type: 'POST',
                            url: '{{ route("attendance.process.store") }}',
                            data: data,
                            success: function (response) {
                                if (response.success) {
                                    processDate(index + 1);
                                } else {
                                    $('.loader').hide();
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
                    }

                    processDate(0);
                });

                $('#save-manual-attendance-btn').on('click', function() {
                    var userIds = [];
                    $('.user-checkbox:checked').each(function() {
                        userIds.push($(this).val());
                    });

                    var data = {
                        users: userIds,
                        date: $('#from_date').val(),
                        clock_in: $('#manual_clock_in').val(),
                        clock_out: $('#manual_clock_out').val(),
                        _token: '{{ csrf_token() }}'
                    };

                    $.ajax({
                        type: 'POST',
                        url: '{{ route("attendance.manual.store") }}',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', response.message, 'success');
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        }
                    });
                });

                // Report filtering
                $('.filter-btn').on('click', function () {
                    var reportType = $(this).closest('.tab-pane').attr('id');
                    var tableId = '#' + reportType + '-report-table';
                    var filterType = $(this).data('filter');
                    var fromDate = $('#from_date').val();
                    var toDate = $('#to_date').val();
                    var userIds = $('.user-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();
                    if(userIds.length === 0){
                        alert('Please select at least one user.');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("attendance.report") }}', // Your Laravel route
                        type: 'POST',
                        data: {
                            report_type: reportType,
                            filter_type: filterType,
                            from_date  : fromDate,
                            to_date    : toDate,
                            user_ids   : userIds,
                            _token: '{{ csrf_token() }}' // CSRF token for Laravel
                        },
                        success: function(response) {

                            var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                            popupWindow.document.write(response); // Write the server response (HTML)
                            popupWindow.focus();
                        },
                        error: function(xhr, status, error) {
                            alert('Something went wrong: ' + error);
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
