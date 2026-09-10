@extends('layouts.default')

@section('title', 'My Attendance')

@section('content')
<style>
    .attn-stat-card {
        border: none !important;
        border-radius: 12px !important;
        padding: 20px !important;
        text-align: center !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .attn-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    .attn-stat-title {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #333333 !important;
        margin-bottom: 8px !important;
    }
    .attn-stat-value {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #212529 !important;
    }
    
    .bg-active-days {
        background-color: #d0f0fd !important; /* Soft Cyan */
    }
    .bg-late-days {
        background-color: #f9d8ee !important; /* Soft Pink */
    }
    .bg-absent-days {
        background-color: #e2e3e5 !important; /* Soft Grey */
    }
    .bg-leave-days {
        background-color: #d4edda !important; /* Soft Mint */
    }

    .attn-filter-card {
        background: #ffffff !important;
        border: 1px solid #eef2f5 !important;
        border-radius: 10px !important;
        padding: 12px 16px !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03) !important;
    }

    .btn-attn-green {
        background-color: #20c997 !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        transition: background-color 0.2s ease;
    }
    .btn-attn-green:hover {
        background-color: #1aa179 !important;
        color: #ffffff !important;
    }

    .attn-table {
        background: #ffffff !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }
    .attn-table thead th {
        background-color: #ffffff !important;
        color: #495057 !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        border-bottom: 2px solid #eef2f5 !important;
        padding: 14px 16px !important;
    }
    .attn-table tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa !important;
    }
    .attn-table tbody tr:nth-of-type(even) {
        background-color: #ffffff !important;
    }
    .attn-table tbody td {
        padding: 14px 16px !important;
        color: #495057 !important;
        font-size: 14px !important;
        border-bottom: 1px solid #f1f3f5 !important;
    }
</style>

<div class="container-fluid py-3">
    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center mb-3">
        <span class="text-muted me-2"><i class="im im-icon-Home me-1"></i>Home</span>
        <span class="text-muted me-2">/</span>
        <span class="fw-bold text-dark">Attendance</span>
    </div>

    {{-- Top 4 Summary Cards --}}
    {{-- Top 4 Summary Cards (Compact System Design) --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0" style="background: #ffffff; border-left: 4px solid #0d6efd !important; border-radius: 10px; padding: 10px 14px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold" style="font-size: 13px;">Active Days</span>
                        <h4 class="mb-0 fw-bold" style="color: #0d6efd; font-size: 22px;" id="stat_active_days">{{ $activeCount }}</h4>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #e7f1ff; color: #0d6efd;">
                        <i class="im im-icon-Calendar-4 fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0" style="background: #ffffff; border-left: 4px solid #ff9800 !important; border-radius: 10px; padding: 10px 14px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold" style="font-size: 13px;">Late Days</span>
                        <h4 class="mb-0 fw-bold" style="color: #ff9800; font-size: 22px;" id="stat_late_days">{{ $lateCount }}</h4>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #fff3e0; color: #ff9800;">
                        <i class="im im-icon-Clock fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0" style="background: #ffffff; border-left: 4px solid #dc3545 !important; border-radius: 10px; padding: 10px 14px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold" style="font-size: 13px;">Absent</span>
                        <h4 class="mb-0 fw-bold" style="color: #dc3545; font-size: 22px;" id="stat_absent_days">{{ $absentCount }}</h4>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #ffebee; color: #dc3545;">
                        <i class="im im-icon-Close-Window fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm border-0" style="background: #ffffff; border-left: 4px solid #198754 !important; border-radius: 10px; padding: 10px 14px;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold" style="font-size: 13px;">Taking Leave</span>
                        <h4 class="mb-0 fw-bold" style="color: #198754; font-size: 22px;" id="stat_leave_days">{{ $leaveCount }}</h4>
                    </div>
                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #e8f5e9; color: #198754;">
                        <i class="im im-icon-Plane fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Actions Control Bar --}}
    <div class="attn-filter-card mb-4">
        <form id="attendanceFilterForm" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-muted small fw-semibold">Select Date</label>
                <input type="date" name="date" id="filter_date" class="form-control bg-light border-1" value="{{ $selectedDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-semibold">Select Month</label>
                <select name="month" id="filter_month" class="form-select bg-light border-1">
                    @php
                        $months = [
                            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                            '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                        ];
                    @endphp
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }} {{ $selectedYear }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-semibold">Select Year</label>
                <select name="year" id="filter_year" class="form-select bg-light border-1">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-end">
                <button type="button" id="btn_get_job_card" class="btn btn-attn-green">
                    Get Job Card
                </button>
            </div>
        </form>
    </div>

    {{-- Attendance Data Table --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table attn-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">Sl</th>
                        <th>Date</th>
                        <th>Punch In</th>
                        <th>Punch Out</th>
                        <th>Late</th>
                        <th>Office Hour</th>
                    </tr>
                </thead>
                <tbody id="attendance_table_body">
                    @forelse($dailyList as $row)
                        <tr>
                            <td>{{ $row['sl'] }}</td>
                            <td class="fw-semibold">{{ $row['date'] }}</td>
                            <td>
                                @if($row['punch_in'] == 'Off Day')
                                    <span class="text-secondary fw-semibold">Off Day</span>
                                @elseif($row['punch_in'] == 'Absent')
                                    <span class="text-danger fw-semibold">Absent</span>
                                @elseif($row['punch_in'] == 'Taking Leave')
                                    <span class="text-success fw-semibold">Taking Leave</span>
                                @else
                                    <span>{{ $row['punch_in'] }}</span>
                                @endif
                            </td>
                            <td>
                                @if($row['punch_out'] == 'Continue')
                                    <span class="badge bg-primary px-2 py-1">Continue</span>
                                @elseif($row['punch_out'] == 'Off Day')
                                    <span class="text-secondary fw-semibold">Off Day</span>
                                @elseif($row['punch_out'] == 'Absent')
                                    <span class="text-danger fw-semibold">Absent</span>
                                @elseif($row['punch_out'] == 'Taking Leave')
                                    <span class="text-success fw-semibold">Taking Leave</span>
                                @else
                                    <span>{{ $row['punch_out'] }}</span>
                                @endif
                            </td>
                            <td>{{ $row['late'] }}</td>
                            <td>{{ $row['office_hour'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No attendance records found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadMyAttendanceData() {
        let date = $('#filter_date').val();
        let month = $('#filter_month').val();
        let year = $('#filter_year').val();

        $.ajax({
            url: "{{ route('attendance.my') }}",
            type: "GET",
            data: { date: date, month: month, year: year },
            success: function(response) {
                $('#stat_active_days').text(response.active_days);
                $('#stat_late_days').text(response.late_days);
                $('#stat_absent_days').text(response.absent_days);
                $('#stat_leave_days').text(response.leave_days);

                let tbody = $('#attendance_table_body');
                tbody.empty();

                if (response.daily_list && response.daily_list.length > 0) {
                    $.each(response.daily_list, function(idx, item) {
                        let punchInHtml = item.punch_in;
                        if (item.punch_in === 'Off Day') punchInHtml = '<span class="text-secondary fw-semibold">Off Day</span>';
                        else if (item.punch_in === 'Absent') punchInHtml = '<span class="text-danger fw-semibold">Absent</span>';
                        else if (item.punch_in === 'Taking Leave') punchInHtml = '<span class="text-success fw-semibold">Taking Leave</span>';

                        let punchOutHtml = item.punch_out;
                        if (item.punch_out === 'Continue') punchOutHtml = '<span class="badge bg-primary px-2 py-1">Continue</span>';
                        else if (item.punch_out === 'Off Day') punchOutHtml = '<span class="text-secondary fw-semibold">Off Day</span>';
                        else if (item.punch_out === 'Absent') punchOutHtml = '<span class="text-danger fw-semibold">Absent</span>';
                        else if (item.punch_out === 'Taking Leave') punchOutHtml = '<span class="text-success fw-semibold">Taking Leave</span>';

                        let tr = `<tr>
                            <td>${item.sl}</td>
                            <td class="fw-semibold">${item.date}</td>
                            <td>${punchInHtml}</td>
                            <td>${punchOutHtml}</td>
                            <td>${item.late}</td>
                            <td>${item.office_hour}</td>
                        </tr>`;
                        tbody.append(tr);
                    });
                } else {
                    tbody.append('<tr><td colspan="6" class="text-center py-4 text-muted">No attendance records found for this period.</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Failed to load attendance data', xhr);
            }
        });
    }

    $(document).ready(function() {
        $('#filter_date').on('change', function() {
            let val = $(this).val();
            if (val) {
                let parts = val.split('-');
                if (parts.length === 3) {
                    $('#filter_year').val(parts[0]);
                    $('#filter_month').val(parts[1]);
                }
            }
            loadMyAttendanceData();
        });

        $('#filter_month, #filter_year').on('change', function() {
            loadMyAttendanceData();
        });

        $('#btn_get_job_card').on('click', function() {
            let fromDate = $('#filter_year').val() + '-' + $('#filter_month').val() + '-01';
            let toDate = $('#filter_date').val();

            let form = $('<form>', {
                action: "{{ route('attendance.report') }}",
                method: 'POST',
                target: '_blank'
            });

            form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
            form.append($('<input>', { type: 'hidden', name: 'report_type', value: 'other' }));
            form.append($('<input>', { type: 'hidden', name: 'filter_type', value: 'job_card' }));
            form.append($('<input>', { type: 'hidden', name: 'from_date', value: fromDate }));
            form.append($('<input>', { type: 'hidden', name: 'to_date', value: toDate }));

            $('body').append(form);
            form.submit();
            form.remove();
        });
    });
</script>
@endpush
@endsection