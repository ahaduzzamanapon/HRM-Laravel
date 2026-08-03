@extends('layouts.default')

@section('title', 'Bonus Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Bonus Settings & Rules</h3>
            <p class="text-muted mb-0">Manage dynamic festival and performance bonus rules for branches</p>
        </div>
        <div>
            <a href="{{ route('bonuses.create') }}" class="btn btn-primary">
                <i class="im im-icon-Plus me-1"></i> Add Bonus Rule
            </a>
            <a href="{{ route('bonuses.disbursements') }}" class="btn btn-outline-secondary ms-2">
                <i class="im im-icon-File-Chart me-1"></i> View Disbursements
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('bonuses.index') }}" class="row g-3 align-items-center">
                @if(isSuperAdmin())
                    <div class="col-md-4">
                        <select name="branch_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- All Branches --</option>
                            @foreach($branches as $id => $name)
                                <option value="{{ $id }}" {{ request('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-auto ms-auto">
                    <a href="{{ route('bonuses.index') }}" class="btn btn-light btn-sm">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Branch</th>
                            <th>Month</th>
                            <th>Bonus Type</th>
                            <th>Base</th>
                            <th>Rate / Amount</th>
                            <th>Religion</th>
                            <th>Min. Service</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonusSettings as $setting)
                            <tr>
                                <td class="fw-bold">{{ $setting->title }}</td>
                                <td>
                                    @if($setting->branch)
                                        <span class="badge bg-info text-dark">{{ $setting->branch->branch_name }}</span>
                                    @else
                                        <span class="badge bg-secondary">All Branches</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($setting->bonus_month)->format('M Y') }}</td>
                                <td>
                                    <span class="badge {{ $setting->bonus_type == 'percentage' ? 'bg-primary' : 'bg-success' }}">
                                        {{ ucfirst($setting->bonus_type) }}
                                    </span>
                                </td>
                                <td><code>{{ str_replace('_', ' ', strtoupper($setting->calculation_base)) }}</code></td>
                                <td class="fw-bold">
                                    @if($setting->bonus_type == 'percentage')
                                        {{ $setting->amount_percentage }}%
                                    @else
                                        {{ number_format($setting->amount_percentage, 2) }}
                                    @endif
                                </td>
                                <td>{{ $setting->religion ?: 'All Religions' }}</td>
                                <td>{{ $setting->min_service_months ? $setting->min_service_months . ' months' : 'None' }}</td>
                                <td>
                                    @if($setting->status == 'processed')
                                        <span class="badge bg-success">Processed</span>
                                    @elseif($setting->status == 'active')
                                        <span class="badge bg-primary">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('bonuses.process', $setting->id) }}" class="btn btn-sm btn-outline-success me-1" title="Calculate & Disburse">
                                        <i class="im im-icon-Calculator"></i> Process
                                    </a>
                                    <a href="{{ route('bonuses.edit', $setting->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="im im-icon-Edit"></i>
                                    </a>
                                    <form action="{{ route('bonuses.destroy', $setting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bonus rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="im im-icon-Trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="im im-icon-Information display-6 d-block mb-2 text-secondary"></i>
                                    No bonus settings configured yet. Click "Add Bonus Rule" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bonusSettings->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                {{ $bonusSettings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
