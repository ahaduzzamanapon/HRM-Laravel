@extends('layouts.default')

@section('title', 'Yearly Interest Processing')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">Yearly Interest Processing</h4>
            
            <form class="d-flex" action="{{ route('pf.reports.yearly') }}" method="GET">
                <input type="number" name="year" class="form-control me-2" value="{{ $year }}" required>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <form action="{{ route('pf.reports.calculate_interest') }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <input type="hidden" name="year" value="{{ $year }}">
                        <label class="me-2 fw-bold">Interest Rate (%):</label>
                        <input type="number" step="0.01" name="interest_rate" class="form-control w-25 me-3" required>
                        <button type="submit" class="btn btn-success">Process Year {{ $year }} Interest</button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee</th>
                                    <th>PF Account</th>
                                    <th>Balance Before Interest</th>
                                    <th>Interest Amount</th>
                                    <th>Balance After Interest</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interests as $index => $interest)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $interest->employee->name }} {{ $interest->employee->last_name }}</td>
                                    <td>{{ $interest->employee->pf_account_number }}</td>
                                    <td>{{ number_format($interest->balance_before, 2) }}</td>
                                    <td><span class="text-success">+{{ number_format($interest->interest_amount, 2) }}</span></td>
                                    <td class="fw-bold">{{ number_format($interest->balance_after, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        $('#datatable').DataTable();
    });
</script>
@endsection
