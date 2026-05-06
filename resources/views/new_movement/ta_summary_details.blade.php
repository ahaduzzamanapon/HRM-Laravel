@extends('layouts.default')
@section('title', 'TA Summary Details')
@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fa fa-user me-2 text-primary"></i>TA Details — {{ $employee->name }} {{ $employee->last_name }}</h4>
        <a href="{{ route('new-movement.ta-summary') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left me-1"></i>Back</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Start Location</th>
                            <th>Date</th>
                            <th>TA Status</th>
                            <th>Applied (৳)</th>
                            <th>Approved (৳)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                        @php $tc = ['pending'=>'warning','hr_approved'=>'info','approved'=>'success','handed_over'=>'primary'] @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $m->start_location }}</td>
                            <td>{{ $m->start_time ? \Carbon\Carbon::parse($m->start_time)->format('d M Y') : '—' }}</td>
                            <td><span class="badge bg-{{ $tc[$m->ta_status] ?? 'secondary' }}">{{ str_replace('_',' ',ucfirst($m->ta_status)) }}</span></td>
                            <td>৳{{ number_format($m->ta_amount, 2) }}</td>
                            <td>৳{{ number_format($m->ta_app_amt, 2) }}</td>
                            <td><a href="{{ route('new-movement.details', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
