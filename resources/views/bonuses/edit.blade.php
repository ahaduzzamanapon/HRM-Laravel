@extends('layouts.default')

@section('title', 'Edit Bonus Rule')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="im im-icon-Edit me-2"></i>Edit Bonus Rule</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('bonuses.update', $bonusSetting->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Bonus Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $bonusSetting->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Target Month <span class="text-danger">*</span></label>
                                <input type="month" name="bonus_month" class="form-control @error('bonus_month') is-invalid @enderror" value="{{ old('bonus_month', \Carbon\Carbon::parse($bonusSetting->bonus_month)->format('Y-m')) }}" required>
                                @error('bonus_month')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        @if(isSuperAdmin())
                            <div class="mb-3">
                                <label class="form-label fw-bold">Branch</label>
                                <select name="branch_id" class="form-select">
                                    <option value="">-- All Branches --</option>
                                    @foreach($branches as $id => $name)
                                        <option value="{{ $id }}" {{ old('branch_id', $bonusSetting->branch_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Calculation Base <span class="text-danger">*</span></label>
                                <select name="calculation_base" class="form-select" required>
                                    <option value="basic_salary" {{ old('calculation_base', $bonusSetting->calculation_base) == 'basic_salary' ? 'selected' : '' }}>Basic Salary</option>
                                    <option value="gross_salary" {{ old('calculation_base', $bonusSetting->calculation_base) == 'gross_salary' ? 'selected' : '' }}>Gross Salary</option>
                                    <option value="fixed" {{ old('calculation_base', $bonusSetting->calculation_base) == 'fixed' ? 'selected' : '' }}>Fixed Flat Amount</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Bonus Type <span class="text-danger">*</span></label>
                                <select name="bonus_type" class="form-select" required>
                                    <option value="percentage" {{ old('bonus_type', $bonusSetting->bonus_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('bonus_type', $bonusSetting->bonus_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Rate / Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="amount_percentage" class="form-control @error('amount_percentage') is-invalid @enderror" value="{{ old('amount_percentage', $bonusSetting->amount_percentage) }}" required>
                                @error('amount_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Religion Filter</label>
                                <select name="religion" class="form-select">
                                    <option value="">All Religions</option>
                                    <option value="Islam" {{ old('religion', $bonusSetting->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Hinduism" {{ old('religion', $bonusSetting->religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                    <option value="Christianity" {{ old('religion', $bonusSetting->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                    <option value="Buddhism" {{ old('religion', $bonusSetting->religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Min. Service Duration (Months)</label>
                                <input type="number" min="0" name="min_service_months" class="form-control" value="{{ old('min_service_months', $bonusSetting->min_service_months) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Remarks / Notes</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $bonusSetting->remarks) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('bonuses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="im im-icon-Save me-1"></i> Update Bonus Rule</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
