@extends('layouts.default')

@section('title')
    Payroll @parent
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
            background: #cdcece;
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
                        <h1 class="mb-5">Payroll</h1>
                        <div class="row">
                            <div class="form-group mt-10">
                                <span style="display: flex; gap: 10px;">
                                    <input type="month" name="salary_month" class="form-control" id="salary_month" value="{{ request('salary_month', date('Y-m')) }}" autocomplete="off">
                                    <button class="btn btn-primary" id="process-salary-btn">Process</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav_t" id="myTab" role="tablist" style="background: #c7e6f8">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab" aria-controls="daily" aria-selected="true">Report</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="daily" role="tabpanel" aria-labelledby="daily-tab">
                                <button class="btn btn-sm btn-primary filter-btn" data-filter="all" id = "salary_sheet">Salary Sheet</button>
                                <button class="btn btn-sm btn-primary filter-btn" data-filter="all" id = "payslip">Salary Slip</button>
                                <button class="btn btn-sm btn-primary filter-btn" data-filter="all" id = "tax">Tax</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="payroll-employee-search" class="form-control" placeholder="Search by Employee Name or ID...">
                        </div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered align-middle" id="user-table">
                                <thead style="position: sticky; top: 0; background-color: #fff; z-index: 1;">
                                    <tr>
                                        <th style="width: 40px;"><input type="checkbox" id="select-all"></th>
                                        <th>Employee (Name / ID)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td><input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox"></td>
                                            <td>
                                                <strong>{{ $user->name }} {{ $user->last_name ?? '' }}</strong>
                                                <br>
                                                <small class="text-muted"><i class="fa fa-id-badge me-1"></i>ID: {{ $user->emp_id ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- user selection end --}}
        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {

                $('#select-all').on('click', function () {
                    $('.user-checkbox:visible').prop('checked', $(this).prop('checked'));
                });

                $('#payroll-employee-search').on('keyup input', function () {
                    var query = $(this).val().toLowerCase().trim();
                    $('#user-table tbody tr').each(function () {
                        var text = $(this).text().toLowerCase();
                        if (text.indexOf(query) !== -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                $('#process-salary-btn').on('click', function (e) {
                    e.preventDefault();
                    $('.loader').show();

                    var userIds = [];
                    $('.user-checkbox:checked').each(function () {
                        userIds.push($(this).val());
                    });

                    var data = {
                        salary_month: $('#salary_month').val(),
                        users: userIds,
                        _token: '{{ csrf_token() }}'
                    };

                    $.ajax({
                        type: 'POST',
                        url: '{{ route("payroll.process") }}',
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
            });
            $(document).ready(function () {
                
                $('#salary_sheet').on('click', function () {
                    var userIds = $('.user-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();
                    if (userIds.length === 0) {
                        alert('No users selected!');
                        return;
                    }
                    salary_month = $('#salary_month').val();
                    // Send data via AJAX POST
                    $.ajax({
                        url: '{{ route("payroll.salarySheet") }}', // Your Laravel route
                        type: 'POST',
                        data: {
                            user_ids: userIds,
                            salary_month: salary_month,
                            _token: '{{ csrf_token() }}' // CSRF token for Laravel
                        },
                        success: function(response) {
                            // Open the response in a new popup window
                            var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                            popupWindow.document.write(response); // Write the server response (HTML)
                            popupWindow.focus();
                        },
                        error: function(xhr, status, error) {
                            alert('Something went wrong: ' + error);
                        }
                    });
                });

                $('#payslip').on('click', function () {
                    var userIds = $('.user-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();
                    if (userIds.length === 0) {
                        alert('No users selected!');
                        return;
                    }
                    // Send data via AJAX POST
                    $.ajax({
                        url: '{{ route("payroll.payslip") }}', // Your Laravel route
                        type: 'POST',
                        data: {
                            user_ids: userIds,
                            salary_month: $('#salary_month').val(),
                            _token: '{{ csrf_token() }}' // CSRF token for Laravel
                        },
                        success: function(response) {
                            // Open the response in a new popup window
                            var popupWindow = window.open('', '_blank', 'width=1000,height=700,left=' + (screen.width/2 - 500) + ',top=' + (screen.height/2 - 350));
                            popupWindow.document.write(response); // Write the server response (HTML)
                            popupWindow.focus();
                        },
                        error: function(xhr, status, error) {
                            alert('Something went wrong: ' + error);
                        }
                    });
                });

                $('#tax').on('click', function () {
                    var userIds = $('.user-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();
                    if (userIds.length === 0) {
                        alert('No users selected!');
                        return;
                    }
                    if (userIds.length > 1) {
                        alert('Please select only one user');
                        return;
                    }
                    // Send data via AJAX POST
                    $.ajax({
                        url: '{{ route("payroll.tax") }}', // Your Laravel route
                        type: 'POST',
                        data: {
                            user_ids: userIds,
                            salary_month: $('#salary_month').val(),
                            _token: '{{ csrf_token() }}' // CSRF token for Laravel
                        },
                        success: function(response) {
                            // Open the response in a new popup window
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
