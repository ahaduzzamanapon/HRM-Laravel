@extends('layouts.default')

@section('title', 'My Profile')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 18px 24px;
        color: #ffffff;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
    }
    .profile-header::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .profile-header::after {
        content: "";
        position: absolute;
        bottom: -60%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.4);
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .profile-name {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #ffffff !important;
    }
    .profile-role-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        color: #ffffff;
        backdrop-filter: blur(10px);
    }
    .info-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.2s;
        height: 100%;
    }
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .info-card .card-header {
        background: transparent;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        font-size: 1rem;
        padding: 10px 16px;
    }
    .info-card .card-header i {
        color: #2a5298;
        margin-right: 8px;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.88rem;
    }
    .info-value {
        font-weight: 600;
        color: #2d3436;
        text-align: right;
    }
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .quick-stat {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 12px;
        width: 100%;
        height: 100%;
    }
    .quick-stat .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .quick-stat .stat-content {
        text-align: left;
    }
    .quick-stat .stat-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #2d3436;
        line-height: 1.1;
    }
    .quick-stat .stat-label {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 1px;
    }
</style>

@php
    $roleName = strtolower(optional($user->role)->name ?? '');
    $roleKey  = strtolower(optional($user->role)->key ?? '');
    $isEmployee = ($roleName === 'employee' || $roleKey === 'employee' || str_contains($roleName, 'employee'));
@endphp

<div class="container-fluid">
    {{-- Profile Header --}}
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ asset($user->image ?? 'assets/images/avatars/01.png') }}"
                     alt="Profile Photo" class="profile-avatar">
            </div>
            <div class="col">
                <h2 class="profile-name">{{ $user->name }} {{ $user->last_name }}</h2>
                <p class="mb-2" style="opacity: 0.85; font-size: 1.05rem;">
                    {{ $user->designation->desi_name ?? 'N/A' }}
                </p>
                <span class="profile-role-badge">
                    <i class="im im-icon-Shield"></i>
                    {{ $user->role->name ?? 'N/A' }}
                </span>
                @if($user->emp_id)
                    <span class="profile-role-badge ms-2">
                        <i class="im im-icon-ID-Card"></i>
                        ID: {{ $user->emp_id }}
                    </span>
                @endif
                <span class="profile-role-badge ms-2">
                    @if($user->status == 'active' || $user->status == 'admin' || $user->status == 'regular')
                        <i class="im im-icon-Yes"></i> Active
                    @else
                        <i class="im im-icon-Close"></i> {{ ucfirst($user->status ?? 'N/A') }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Quick Stats (Visible to Employee Role Only) --}}
    @if($isEmployee)
    <div class="row g-2 mb-3">
        <div class="col-md-3 col-6 mb-1">
            <div class="card info-card">
                <div class="quick-stat">
                    <div class="stat-icon" style="background: #e3f2fd; color: #1565c0;">
                        <i class="im im-icon-Calendar-4"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">
                            @if($user->date_of_join)
                                {{ \Carbon\Carbon::parse($user->date_of_join)->diffInYears(now()) }}
                            @else
                                N/A
                            @endif
                        </div>
                        <div class="stat-label">Years of Service</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-1">
            <div class="card info-card">
                <div class="quick-stat">
                    <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="im im-icon-Calendar-4"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $leaveBalance ?? 0 }}</div>
                        <div class="stat-label">Leave Taken (This Year)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-1">
            <div class="card info-card">
                <div class="quick-stat">
                    <div class="stat-icon" style="background: #fff3e0; color: #e65100;">
                        <i class="im im-icon-Medal-2"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->promotionDetails->count() }}</div>
                        <div class="stat-label">Promotions</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-1">
            <div class="card info-card">
                <div class="quick-stat">
                    <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                        <i class="im im-icon-Certificate"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->trainingDetails->count() }}</div>
                        <div class="stat-label">Trainings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        {{-- Personal Information --}}
        <div class="col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-header">
                    <i class="im im-icon-User"></i> Personal Information
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">Full Name</span>
                        <span class="info-value">{{ $user->name }} {{ $user->last_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $user->phone_number ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date of Birth</span>
                        <span class="info-value">
                            {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d M, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Gender</span>
                        <span class="info-value">{{ ucfirst($user->gender ?? 'N/A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Blood Group</span>
                        <span class="info-value">{{ $user->blood_group ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Religion</span>
                        <span class="info-value">{{ ucfirst($user->religion ?? 'N/A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Marital Status</span>
                        <span class="info-value">{{ ucfirst($user->marital_status ?? 'N/A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value" style="max-width: 60%; text-align: right;">{{ $user->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-header">
                    <i class="im im-icon-Business-Man"></i> Employment Details
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">Employee ID</span>
                        <span class="info-value">{{ $user->emp_id ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Designation</span>
                        <span class="info-value">{{ $user->designation->desi_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Department</span>
                        <span class="info-value">{{ $user->department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Branch</span>
                        <span class="info-value">{{ $user->branch->branch_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Shift</span>
                        <span class="info-value">{{ $user->shift->shift_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Employee Type</span>
                        <span class="info-value">{{ ucfirst($user->emp_type ?? 'N/A') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date of Joining</span>
                        <span class="info-value">
                            {{ $user->date_of_join ? \Carbon\Carbon::parse($user->date_of_join)->format('d M, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Role</span>
                        <span class="info-value">{{ $user->role->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">PF Member</span>
                        <span class="info-value">
                            @if($user->is_pf_member)
                                <span class="status-badge status-active">Yes</span>
                            @else
                                <span class="status-badge status-inactive">No</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Row (Visible to Employee Role Only) --}}
    @if($isEmployee)
    <div class="row">
        {{-- Recent Leave Applications --}}
        <div class="col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="im im-icon-Calendar-4"></i> Recent Leave Applications</span>
                </div>
                <div class="card-body">
                    @if($recentLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr style="border-bottom: 2px solid #f0f0f0;">
                                        <th style="font-size: 0.85rem; color: #6c757d;">Type</th>
                                        <th style="font-size: 0.85rem; color: #6c757d;">From</th>
                                        <th style="font-size: 0.85rem; color: #6c757d;">To</th>
                                        <th style="font-size: 0.85rem; color: #6c757d;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves as $leave)
                                        <tr>
                                            <td style="font-size: 0.85rem;">{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                            <td style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M') }}</td>
                                            <td style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M') }}</td>
                                            <td>
                                                @if($leave->status == 'approved')
                                                    <span class="status-badge status-active">Approved</span>
                                                @elseif($leave->status == 'pending')
                                                    <span class="status-badge" style="background: #fff3cd; color: #856404;">Pending</span>
                                                @else
                                                    <span class="status-badge status-inactive">{{ ucfirst($leave->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0 py-3">No recent leave applications</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Transfers --}}
        <div class="col-md-6 mb-4">
            <div class="card info-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="im im-icon-Location-2"></i> Recent Transfers</span>
                </div>
                <div class="card-body">
                    @if($user->transferDetails->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0">
                                <thead>
                                    <tr style="border-bottom: 2px solid #f0f0f0;">
                                        <th style="font-size: 0.85rem; color: #6c757d;">Date</th>
                                        <th style="font-size: 0.85rem; color: #6c757d;">From</th>
                                        <th style="font-size: 0.85rem; color: #6c757d;">To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->transferDetails->sortByDesc('created_at')->take(5) as $transfer)
                                        <tr>
                                            <td style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M, Y') }}</td>
                                            <td style="font-size: 0.85rem;">{{ $transfer->from_branch ?? 'N/A' }}</td>
                                            <td style="font-size: 0.85rem;">{{ $transfer->to_branch ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0 py-3">No transfer records</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
