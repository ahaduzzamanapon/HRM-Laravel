@extends('layouts.default')

@section('title')
PF Schemes @parent
@stop

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>PF Schemes</h1>
            </div>
            <div class="col-sm-6" style="text-align: right;">
                <a href="{{ route('pf.schemes.create') }}" class="btn btn-primary" style="border-radius: 20px; padding: 6px 20px;"><i class="fa fa-plus"></i> Create New Scheme</a>
                <a href="{{ route('pf.dashboard') }}" class="btn btn-secondary" style="border-radius: 20px; padding: 6px 20px;">Back to Dashboard</a>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="d_card" style="background: aliceblue;">
                    <div class="row" style="display: flex;flex-direction: row;align-items: center; padding: 0 15px; margin-bottom: 20px;">
                        <h4>Manage PF Schemes</h4>
                    </div>

                    <div class="card-body p-0 bg-white" style="border-radius: 10px; overflow: hidden; box-shadow: 0px 0px 8px 2px #bdbdbd;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead style="background: #0177bc; color: white;">
                                    <tr>
                                        <th style="padding: 10px; color: #fff;">ID</th>
                                        <th style="padding: 10px; color: #fff;">Scheme Name</th>
                                        <th style="padding: 10px; color: #fff;">Employee Contribution (%)</th>
                                        <th style="padding: 10px; color: #fff;">Employer Contribution (%)</th>
                                        <th style="padding: 10px; color: #fff; text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schemes as $scheme)
                                    <tr>
                                        <td>{{ $scheme->id }}</td>
                                        <td style="font-weight: bold;">{{ $scheme->name }}</td>
                                        <td>{{ $scheme->employee_contribution_percentage }}%</td>
                                        <td>{{ $scheme->employer_contribution_percentage }}%</td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('pf.schemes.edit', $scheme->id) }}" class="btn btn-sm btn-info" style="border-radius: 15px;"><i class="fa fa-edit"></i> Edit</a>
                                            <form action="{{ route('pf.schemes.destroy', $scheme->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 15px;" onclick="return confirm('Are you sure you want to delete this scheme?')"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No PF Schemes found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
