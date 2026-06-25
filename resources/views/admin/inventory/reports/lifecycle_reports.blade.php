@extends('layouts.default')

@section('title')
Lifecycle Reports @parent
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Lifecycle Reports</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.inventory.reports.lifecycle', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                <a href="{{ route('admin.inventory.reports.lifecycle', array_merge(request()->all(), ['export' => 'excel'])) }}" class="btn btn-success ml-2"><i class="fa fa-file-excel-o"></i> Export Excel</a>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.reports.lifecycle') }}" class="row mb-4">
                <div class="form-group col-md-4">
                    <label>Asset</label>
                    {!! Form::select('asset_id', ['' => 'All Assets'] + $data['assets_list']->toArray(), request('asset_id'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-4">
                    <label>Date (From)</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Date (To)</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.inventory.reports.lifecycle') }}" class="btn btn-default ml-2">Clear</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Asset</th>
                            <th>Action</th>
                            <th>Performed By</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ optional($log->asset)->name }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ optional($log->user)->name ?? 'System' }}</td>
                                <td>{{ $log->details }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No logs found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
