@extends('layouts.default')

@section('title')
My Attendance @parent
@stop

@section('content')
<section class="content-header">
    <h1>
        My Attendance
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ url('/') }}">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="active">
            My Attendance
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="my-attendance-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Late</th>
                                    <th>Early Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#my-attendance-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("attendance.report") }}',
            columns: [
                { data: 'date', name: 'date' },
                { data: 'clock_in', name: 'clock_in' },
                { data: 'clock_out', name: 'clock_out' },
                { data: 'late', name: 'late' },
                { data: 'early_out', name: 'early_out' },
                { data: 'status', name: 'status' },
            ]
        });
    });
</script>
@endpush