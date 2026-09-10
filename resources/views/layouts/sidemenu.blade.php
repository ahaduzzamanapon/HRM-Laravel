{{-- User Info Section --}}
@auth
    <style>
        .user-panel {
            background-image: url("{{ asset('assets/images/user-panel-img.jpg') }}");
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .user-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="user-info text-center py-3 user-panel">
        <img src="{{ asset(Auth::user()->image ?? 'assets/images/avatars/01.png') }}" alt="User Image"
            class="img-fluid rounded-circle mb-2" style="width: 60px;height: 60px;object-fit: cover;">
        <h5 style="color: white;" class="mb-0">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h5>
        <p style="color: white;" class="mb-0">{{ Auth::user()->email }}</p>
        <p style="color: white;" class="mb-0"><small>{{ Auth::user()->role->name ?? 'N/A' }}</small></p>
    </div>
@endauth

{{-- Dashboard --}}
@if(can('dashboard'))
    <li class="nav-item">
        <a class="nav-link {!! Request::is('/') || Request::is('dashboard') ? 'active' : '' !!}" aria-current="page" href="{{ url('/') }}">
            <i class="icon im im-icon-Home"></i>
            <span class="item-name">Dashboard</span>
        </a>
    </li>
@endif

{{-- My Profile --}}
@if(can('my_profile'))
    <li class="nav-item">
        <a class="nav-link {!! Request::is('my-profile') ? 'active' : '' !!}" href="{{ route('profile') }}">
            <i class="icon im im-icon-ID-Card"></i>
            <span class="item-name">My Profile</span>
        </a>
    </li>
@endif

{{-- Staff Management --}}
@if(can('staff_management') && (can('view_employees') || can('employee_departures') || can('view_employee_departures')))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('users*') || Request::is('employeeDepartures*') ? 'active' : '') !!}" data-bs-toggle="collapse" href="#users_menu"
            role="button" aria-expanded="false" aria-controls="users_menu">
            <i class="icon im im-icon-User"></i>
            <span class="item-name">Staff</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! (Request::is('users*') || Request::is('employeeDepartures*') ? 'show' : '') !!}" id="users_menu"
            data-bs-parent="#sidebar-menu">
            @if(can('view_employees'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('users*') ? 'active' : '' !!}" href="{{ route('users.index') }}">
                        <i class="icon im im-icon-User"></i>
                        <i class="sidenav-mini-icon"> U </i>
                        <span class="item-name">Employees</span>
                    </a>
                </li>
            @endif
            @if(can('employee_departures') || can('view_employee_departures'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('employeeDepartures*') ? 'active' : '' !!}" href="{{ route('employeeDepartures.index') }}">
                        <i class="icon im im-icon-Remove-User"></i>
                        <i class="sidenav-mini-icon"> DE </i>
                        <span class="item-name">Departed Employees</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Organization --}}
@if(can('organization') && (can('manage_designations') || can('manage_departments') || can('rewardings') || can('innovations') || can('manage_branches')))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('designations*') || Request::is('departments*') || Request::is('branches*') || Request::is('rewardings*') || Request::is('innovations*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#organization_menu" role="button" aria-expanded="false"
            aria-controls="organization_menu">
            <i class="icon im im-icon-Gear"></i>
            <span class="item-name">Organization</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! (Request::is('designations*') || Request::is('departments*') || Request::is('branches*') || Request::is('rewardings*') || Request::is('innovations*') ? 'show' : '') !!}"
            id="organization_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_designations'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('designations*') ? 'active' : '' !!}"
                        href="{{ route('designations.index') }}">
                        <i class="icon im im-icon-Teacher"></i>
                        <i class="sidenav-mini-icon"> D </i>
                        <span class="item-name">Designations</span>
                    </a>
                </li>
            @endif
            @if(can('manage_departments'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('departments*') ? 'active' : '' !!}"
                        href="{{ route('departments.index') }}">
                        <i class="icon im im-icon-Teacher"></i>
                        <i class="sidenav-mini-icon"> D </i>
                        <span class="item-name">Departments</span>
                    </a>
                </li>
            @endif
            <!-- @if(can('rewardings'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('rewardings*') ? 'active' : '' !!}"
                        href="{{ route('rewardings.index') }}">
                        <i class="icon im im-icon-Teacher"></i>
                        <i class="sidenav-mini-icon"> R </i>
                        <span class="item-name">Rewarding</span>
                    </a>
                </li>
            @endif
            @if(can('innovations'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('innovations*') ? 'active' : '' !!}"
                        href="{{ route('innovations.index') }}">
                        <i class="icon im im-icon-Idea"></i>
                        <i class="sidenav-mini-icon"> I </i>
                        <span class="item-name">Innovations</span>
                    </a>
                </li>
            @endif -->
            @if(can('manage_branches'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('branches*') ? 'active' : '' !!}" href="{{ route('branches.index') }}">
                        <i class="icon im im-icon-Security-Settings"></i>
                        <i class="sidenav-mini-icon"> B </i>
                        <span class="item-name">Branch Management</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- HR --}}
@php
    $sidebarPendingLeaveCount = 0;
    if (Auth::check()) {
        $authUser = Auth::user();
        $isEmp = \App\Services\AuthorizationEngine::isEmployeeRole($authUser);
        if (!$isEmp && (isSuperAdmin() || can('approve_leave') || can('manage_leave_types') || can('manage_leaves'))) {
            $pendingQuery = \App\Models\LeaveApplication::query();
            applyUserBranchScope($pendingQuery, 'user');
            $sidebarPendingLeaveCount = $pendingQuery->where('status', 'Pending')->count();
        }
    }
@endphp
@if(can('hr') && (can('upload_attendance_files') || can('process_attendance') || can('leave_applications') || can('movements') || can('smart_movement') || can('manage_holidays') || can('manage_shifts') || can('manage_leave_types')))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('holydays*') || Request::is('shifts*') || Request::is('attendanceFileUploads*') || Request::is('leaveTypes*') || Request::is('leaveApplications*') || Request::is('movements*') || Request::is('new-movement*') ? 'active' : '') !!}"
        data-bs-toggle="collapse" href="#hr_menu" role="button" aria-expanded="false" aria-controls="hr_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">HR</span>
        @if($sidebarPendingLeaveCount > 0)
            <span class="badge bg-danger rounded-pill ms-2" style="font-size: 11px; padding: 3px 7px;">{{ $sidebarPendingLeaveCount }}</span>
        @endif
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('holydays*') || Request::is('shifts*') || Request::is('attendanceFileUploads*') || Request::is('leaveTypes*') || Request::is('leaveApplications*') || Request::is('movements*') || Request::is('new-movement*') ? 'show' : '') !!}"
        id="hr_menu" data-bs-parent="#sidebar-menu">
        @if(can('upload_attendance_files'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('attendanceFileUploads*') ? 'active' : '' !!}"
                    href="{{ route('attendanceFileUploads.index') }}">
                    <i class="icon im im-icon-Upload-toCloud"></i>
                    <i class="sidenav-mini-icon"> AF </i>
                    <span class="item-name">Attendance File Upload</span>
                </a>
            </li>
        @endif
        @if(can('process_attendance') || can('view_my_attendance') || can('my_attendance'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('attendance/process*') || Request::is('my-attendance*') ? 'active' : '' !!}"
                    href="{{ route('attendance.process.index') }}">
                    <i class="icon im im-icon-Clock-Forward"></i>
                    <i class="sidenav-mini-icon"> AP </i>
                    <span class="item-name">{{ \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user()) ? 'Attendance' : 'Attendance Process' }}</span>
                </a>
            </li>
        @endif
        @if(can('leave_applications'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('leaveApplications*') ? 'active' : '' !!}"
                    href="{{ route('leaveApplications.index') }}">
                    <i class="icon im im-icon-Calendar-4"></i>
                    <i class="sidenav-mini-icon"> LA </i>
                    <span class="item-name">Leave Applications</span>
                    @if($sidebarPendingLeaveCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 11px; padding: 3px 7px;">{{ $sidebarPendingLeaveCount }}</span>
                    @endif
                </a>
            </li>
        @endif
        <!-- @if(can('movements'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('movements*') ? 'active' : '' !!}" href="{{ route('movements.index') }}">
                    <i class="icon im im-icon-Location-2"></i>
                    <i class="sidenav-mini-icon"> M </i>
                    <span class="item-name">Movements</span>
                </a>
            </li>
        @endif -->
        <!-- @if(can('smart_movement'))
        <li class="nav-item">
            <a class="nav-link {!! (Request::is('new-movement*') ? 'active' : '') !!}" data-bs-toggle="collapse" href="#smart_movement_menu"
               role="button" aria-expanded="false" aria-controls="smart_movement_menu">
                <i class="icon im im-icon-Car"></i>
                <span class="item-name">Smart Movement</span>
                <i class="right-icon im im-icon-Arrow-Right"></i>
            </a>
            <ul class="sub-nav collapse {!! Request::is('new-movement*') ? 'show' : '' !!}" id="smart_movement_menu">
                @if(can('view_smart_movement_dashboard'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('new-movement') ? 'active' : '' !!}" href="{{ route('new-movement.index') }}">
                        <i class="icon im im-icon-Dashboard"></i>
                        <i class="sidenav-mini-icon"> D </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                @endif
                @if(can('view_my_movements'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('new-movement/my-dashboard*') ? 'active' : '' !!}" href="{{ route('new-movement.my-dashboard') }}">
                        <i class="icon im im-icon-User"></i>
                        <i class="sidenav-mini-icon"> M </i>
                        <span class="item-name">My Movements</span>
                    </a>
                </li>
                @endif
                @if(can('view_ta_list'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('new-movement/ta-list*') ? 'active' : '' !!}" href="{{ route('new-movement.ta-list') }}">
                        <i class="icon im im-icon-File-Chart"></i>
                        <i class="sidenav-mini-icon"> T </i>
                        <span class="item-name">TA List</span>
                    </a>
                </li>
                @endif
                @if(can('view_ta_summary'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('new-movement/ta-summary*') ? 'active' : '' !!}" href="{{ route('new-movement.ta-summary') }}">
                        <i class="icon im im-icon-File-Chart"></i>
                        <i class="sidenav-mini-icon"> S </i>
                        <span class="item-name">TA Summary</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif -->
        @if(can('manage_holidays'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('holydays*') ? 'active' : '' !!}" href="{{ route('holydays.index') }}">
                    <i class="icon im im-icon-Teacher"></i>
                    <i class="sidenav-mini-icon"> H </i>
                    <span class="item-name">Holyday Management</span>
                </a>
            </li>
        @endif
        @if(can('manage_shifts'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('shifts*') ? 'active' : '' !!}" href="{{ route('shifts.index') }}">
                    <i class="icon im im-icon-Time-Window"></i>
                    <i class="sidenav-mini-icon"> SM </i>
                    <span class="item-name">Shift Management</span>
                </a>
            </li>
        @endif
        @if(can('manage_leave_types'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('leaveTypes*') ? 'active' : '' !!}"
                    href="{{ route('leaveTypes.index') }}">
                    <i class="icon im im-icon-Calendar-4"></i>
                    <i class="sidenav-mini-icon"> LT </i>
                    <span class="item-name">Leave Types</span>
                </a>
            </li> 
        @endif
    </ul>
</li>
@endif

{{-- Payroll --}}
@if(can('payroll') || can('my_payroll') || \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user()))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('payroll*') || Request::is('my-payroll*') || Request::is('childAllowances*') || Request::is('bonuses*') || Request::is('tax-management*') ? 'active' : '') !!}" data-bs-toggle="collapse" href="#payroll_menu"
            role="button" aria-expanded="false" aria-controls="payroll_menu">
            <i class="icon im im-icon-User"></i>
            <span class="item-name">Payroll & Compliance</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! (Request::is('payroll*') || Request::is('my-payroll*') || Request::is('childAllowances*') || Request::is('bonuses*') || Request::is('tax-management*') ? 'show' : '') !!}" id="payroll_menu"
            data-bs-parent="#sidebar-menu">
            @if(can('my_payroll') || \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user()) || isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('my-payroll*') ? 'active' : '' !!}" href="{{ route('my-payroll.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> MP </i>
                        <span class="item-name">My Payroll</span>
                    </a>
                </li>
            @endif
            @if(Route::has('childAllowances.index'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('childAllowances*') ? 'active' : '' !!}" href="{{ route('childAllowances.index') }}">
                        <i class="icon im im-icon-Face-Style"></i>
                        <i class="sidenav-mini-icon"> CA </i>
                        <span class="item-name">Child Allowances</span>
                    </a>
                </li>
            @endif
            @if(can('payroll_process'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('payroll*') && !Request::is('my-payroll*') && !Request::is('childAllowances*') ? 'active' : '' !!}" href="{{ route('payroll.index') }}">
                        <i class="icon im im-icon-Clock-Forward"></i>
                        <i class="sidenav-mini-icon"> P </i>
                        <span class="item-name">Payroll Process</span>
                    </a>
                </li>
            @endif
            @if(can('manage_bonuses'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('bonuses*') ? 'active' : '' !!}" href="{{ route('bonuses.index') }}">
                        <i class="icon im im-icon-Medal-2"></i>
                        <i class="sidenav-mini-icon"> B </i>
                        <span class="item-name">Bonus Management</span>
                    </a>
                </li>
            @endif
            @if(can('tax_management'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('tax-management*') ? 'active' : '' !!}" href="{{ route('taxManagement.index') }}">
                        <i class="icon im im-icon-Calculator"></i>
                        <i class="sidenav-mini-icon"> TM </i>
                        <span class="item-name">Tax Management</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Welfare Fund --}}
<li class="nav-item">
    <a class="nav-link {!! (Request::is('welfare*') || Request::is('employeeChildrenEducationSupports*') || Request::is('funeralSupports*') || Request::is('medicalSupports*') ? 'active' : '') !!}"
        data-bs-toggle="collapse" href="#welfare_fund_menu" role="button" aria-expanded="false"
        aria-controls="welfare_fund_menu">
        <i class="icon im im-icon-Heart"></i>
        <span class="item-name">Welfare Fund</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! Request::is('welfare*') || Request::is('employeeChildrenEducationSupports*') || Request::is('funeralSupports*') || Request::is('medicalSupports*') ? 'show' : '' !!}"
        id="welfare_fund_menu" data-bs-parent="#sidebar-menu">
        
        @if(!\App\Services\AuthorizationEngine::isEmployeeRole() || isSuperAdmin())
            <li class="nav-item">
                <a class="nav-link {!! Request::is('welfare/dashboard*') ? 'active' : '' !!}"
                    href="{{ route('welfare.dashboard') }}">
                    <i class="icon im im-icon-Bar-Chart"></i>
                    <i class="sidenav-mini-icon"> D </i>
                    <span class="item-name">Welfare Dashboard</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link {!! Request::is('welfare/my-statement*') ? 'active' : '' !!}"
                href="{{ route('welfare.myStatement') }}">
                <i class="icon im im-icon-File-TXT"></i>
                <i class="sidenav-mini-icon"> S </i>
                <span class="item-name">My Welfare Statement</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {!! Request::is('employeeChildrenEducationSupports*') ? 'active' : '' !!}"
                href="{{ route('employeeChildrenEducationSupports.index') }}">
                <i class="icon im im-icon-Student-Female"></i>
                <i class="sidenav-mini-icon"> ECES </i>
                <span class="item-name">Children Education Support</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {!! Request::is('funeralSupports*') ? 'active' : '' !!}"
                href="{{ route('funeralSupports.index') }}">
                <i class="icon im im-icon-Coffin"></i>
                <i class="sidenav-mini-icon"> FS </i>
                <span class="item-name">Funeral Support</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {!! Request::is('medicalSupports*') ? 'active' : '' !!}"
                href="{{ route('medicalSupports.index') }}">
                <i class="icon im im-icon-Medical-Sign"></i>
                <i class="sidenav-mini-icon"> MS </i>
                <span class="item-name">Medical Support</span>
            </a>
        </li>

        @if(!\App\Services\AuthorizationEngine::isEmployeeRole() || isSuperAdmin())
            <li class="nav-item">
                <a class="nav-link {!! Request::is('welfare/ledger*') ? 'active' : '' !!}"
                    href="{{ route('welfare.ledger') }}">
                    <i class="icon im im-icon-Bank"></i>
                    <i class="sidenav-mini-icon"> L </i>
                    <span class="item-name">Fund Ledger</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {!! Request::is('welfare/reports*') ? 'active' : '' !!}"
                    href="{{ route('welfare.reports') }}">
                    <i class="icon im im-icon-File-Chart"></i>
                    <i class="sidenav-mini-icon"> R </i>
                    <span class="item-name">Welfare Reports</span>
                </a>
            </li>
        @endif

        @if(isSuperAdmin() || can('manage_welfare_settings'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('welfare/settings*') ? 'active' : '' !!}"
                    href="{{ route('welfare.settings.edit') }}">
                    <i class="icon im im-icon-Gear"></i>
                    <i class="sidenav-mini-icon"> C </i>
                    <span class="item-name">Welfare Settings</span>
                </a>
            </li>
        @endif
    </ul>
</li>

{{-- Disciplinary Actions --}}
@if(can('disciplinary_actions') && (can('view_departmental_cases') || can('view_my_departmental_cases') || can('manage_departmental_cases') || can('manage_penalties')))
    @php
        $deptCaseBadgeCount = 0;
        if (auth()->check()) {
            $authUser = auth()->user();
            $isEmpOnly = !can('manage_departmental_cases') && !can('view_departmental_cases') && !isSuperAdmin();
            $pendingStatuses = ['Pending', 'Under Investigation', 'Show Cause Issued', 'Hearing Scheduled'];
            if ($isEmpOnly) {
                $deptCaseBadgeCount = \App\Models\DepartmentalCase::where('employee_id', $authUser->id)
                    ->whereIn('status', $pendingStatuses)
                    ->count();
            } else {
                $baseDeptQuery = \App\Models\DepartmentalCase::query();
                applyUserBranchScope($baseDeptQuery, 'employee');
                $deptCaseBadgeCount = $baseDeptQuery->whereIn('status', $pendingStatuses)->count();
            }
        }
    @endphp
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('departmentalCases*') || Request::is('penalties*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#disciplinary_actions_menu" role="button" aria-expanded="false"
            aria-controls="disciplinary_actions_menu">
            <i class="icon im im-icon-Hammer"></i>
            <span class="item-name">Disciplinary Actions</span>
            @if($deptCaseBadgeCount > 0)
                <span class="badge rounded-pill bg-danger ms-auto me-2 px-2 py-1" style="font-size: 0.75rem;">{{ $deptCaseBadgeCount }}</span>
            @endif
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! Request::is('departmentalCases*') || Request::is('penalties*') ? 'show' : '' !!}"
            id="disciplinary_actions_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_departmental_cases') || can('view_departmental_cases') || can('view_my_departmental_cases'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('departmentalCases*') ? 'active' : '' !!}"
                        href="{{ route('departmentalCases.index') }}">
                        <i class="icon im im-icon-Folder-Open"></i>
                        <i class="sidenav-mini-icon"> DC </i>
                        <span class="item-name">Departmental Cases</span>
                        @if($deptCaseBadgeCount > 0)
                            <span class="badge rounded-pill bg-danger ms-auto me-1 px-2 py-1" style="font-size: 0.75rem;">{{ $deptCaseBadgeCount }}</span>
                        @endif
                    </a>
                </li>
            @endif
            @if(can('manage_penalties'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('penalties*') ? 'active' : '' !!}" href="{{ route('penalties.index') }}">
                        <i class="icon im im-icon-Warning-Window"></i>
                        <i class="sidenav-mini-icon"> P </i>
                        <span class="item-name">Penalties</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Loans and Advances --}}
@if(can('loans_and_advances') || \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user()) || isSuperAdmin() || can('manage_loans') || can('apply_loans'))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('loanTypes*') || Request::is('loans*') || Request::is('employee-loans*') || Request::is('loanRepayments*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#loans_and_advances_menu" role="button" aria-expanded="false"
            aria-controls="loans_and_advances_menu">
            <i class="icon im im-icon-Money-Bag"></i>
            <span class="item-name">Loans and Advances</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! Request::is('loanTypes*') || Request::is('loans*') || Request::is('employee-loans*') || Request::is('loanRepayments*') ? 'show' : '' !!}"
            id="loans_and_advances_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_loan_types') || isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('loanTypes*') ? 'active' : '' !!}" href="{{ route('loanTypes.index') }}">
                        <i class="icon im im-icon-Align-JustifyAll"></i>
                        <i class="sidenav-mini-icon"> LT </i>
                        <span class="item-name">Loan Types</span>
                    </a>
                </li>
            @endif
            @if(can('manage_loans') || can('apply_loans') || \App\Services\AuthorizationEngine::isEmployeeRole(Auth::user()) || isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('employee-loans*') || Request::is('loans*') ? 'active' : '' !!}" href="{{ route('employeeLoans.index') }}">
                        <i class="icon im im-icon-Coins"></i>
                        <i class="sidenav-mini-icon"> EL </i>
                        <span class="item-name">Employee Loans</span>
                    </a>
                </li>
            @endif
            @if(can('manage_loan_repayments'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('loanRepayments*') ? 'active' : '' !!}"
                        href="{{ route('loanRepayments.index') }}">
                        <i class="icon im im-icon-Money-2"></i>
                        <i class="sidenav-mini-icon"> LR </i>
                        <span class="item-name">Loan Repayments</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Provident Fund --}}
@if((can('provident_fund') || \App\Services\AuthorizationEngine::isEmployeeRole()) && (can('pf_dashboard') || can('manage_pf_schemes') || can('manage_pf_employees') || can('process_pf_contributions') || can('view_pf_reports') || can('pf_withdrawals') || can('pf_loans') || can('view_pf_analytics') || \App\Services\AuthorizationEngine::isEmployeeRole()))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('pf*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#new_pf_menu" role="button" aria-expanded="false"
            aria-controls="new_pf_menu">
            <i class="icon im im-icon-Safe-Box"></i>
            <span class="item-name">Provident Fund</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! Request::is('pf*') ? 'show' : '' !!}"
            id="new_pf_menu" data-bs-parent="#sidebar-menu">
            @if(can('pf_dashboard') || \App\Services\AuthorizationEngine::isEmployeeRole())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/dashboard*') ? 'active' : '' !!}"
                        href="{{ route('pf.dashboard') }}">
                        <i class="icon im im-icon-Bar-Chart"></i>
                        <i class="sidenav-mini-icon"> D </i>
                        <span class="item-name">PF Dashboard</span>
                    </a>
                </li>
            @endif
            @if(can('pf_loans') || \App\Services\AuthorizationEngine::isEmployeeRole())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/loans*') ? 'active' : '' !!}"
                        href="{{ route('pf.loans.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> L </i>
                        <span class="item-name">Loan Applications</span>
                    </a>
                </li>
            @endif
            @if(\App\Services\AuthorizationEngine::isEmployeeRole() || can('view_pf_reports'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/reports/statement*') ? 'active' : '' !!}"
                        href="{{ route('pf.reports.statement') }}">
                        <i class="icon im im-icon-File-TXT"></i>
                        <i class="sidenav-mini-icon"> S </i>
                        <span class="item-name">My PF Statement</span>
                    </a>
                </li>
            @endif
            @if(can('manage_pf_schemes'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/schemes*') ? 'active' : '' !!}"
                        href="{{ route('pf.schemes.index') }}">
                        <i class="icon im im-icon-Gear"></i>
                        <i class="sidenav-mini-icon"> S </i>
                        <span class="item-name">PF Settings</span>
                    </a>
                </li>
            @endif
            @if(can('manage_pf_employees'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/employees*') ? 'active' : '' !!}"
                        href="{{ route('pf.employees.index') }}">
                        <i class="icon im im-icon-User"></i>
                        <i class="sidenav-mini-icon"> E </i>
                        <span class="item-name">Employee List</span>
                    </a>
                </li>
            @endif
            @if(can('process_pf_contributions'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/contributions*') ? 'active' : '' !!}"
                        href="{{ route('pf.contributions.index') }}">
                        <i class="icon im im-icon-File-Chart"></i>
                        <i class="sidenav-mini-icon"> C </i>
                        <span class="item-name">Monthly Contributions</span>
                    </a>
                </li>
            @endif
            @if(can('view_pf_reports') && !\App\Services\AuthorizationEngine::isEmployeeRole())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/reports/yearly*') ? 'active' : '' !!}"
                        href="{{ route('pf.reports.yearly') }}">
                        <i class="icon im im-icon-Calendar-4"></i>
                        <i class="sidenav-mini-icon"> Y </i>
                        <span class="item-name">Yearly Reports</span>
                    </a>
                </li>
            @endif
            @if(can('pf_withdrawals') && !\App\Services\AuthorizationEngine::isEmployeeRole())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/withdrawals*') ? 'active' : '' !!}"
                        href="{{ route('pf.withdrawals.index') }}">
                        <i class="icon im im-icon-Hand-Touch"></i>
                        <i class="sidenav-mini-icon"> W </i>
                        <span class="item-name">Withdrawal Requests</span>
                    </a>
                </li>
            @endif
            @if(can('view_pf_analytics') && !\App\Services\AuthorizationEngine::isEmployeeRole())
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('pf/reports/analytics*') ? 'active' : '' !!}"
                        href="{{ route('pf.reports.analytics') }}">
                        <i class="icon im im-icon-Bar-Chart"></i>
                        <i class="sidenav-mini-icon"> R </i>
                        <span class="item-name">Reports & Analytics</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Pension Module --}}
@if(can('pension') && (can('manage_pension_policies') || can('manage_pension_eligibility') || can('manage_pension_calculations') || can('manage_pension_disbursements') || can('manage_arrear_bills') || can('view_pension_reports')))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('admin/pension*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#pension_menu" role="button" aria-expanded="false"
            aria-controls="pension_menu">
            <i class="icon im im-icon-Bank"></i>
            <span class="item-name">Pension</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! Request::is('admin/pension*') ? 'show' : '' !!}"
            id="pension_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_pension_policies'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/policies*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.policies.index') }}">
                        <i class="icon im im-icon-File"></i>
                        <i class="sidenav-mini-icon"> PP </i>
                        <span class="item-name">Policies & Schemes</span>
                    </a>
                </li>
            @endif
            @if(can('manage_pension_eligibility'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/eligibility*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.eligibility.index') }}">
                        <i class="icon im im-icon-Checked-User"></i>
                        <i class="sidenav-mini-icon"> EC </i>
                        <span class="item-name">Eligibility Checks</span>
                    </a>
                </li>
            @endif
            @if(can('manage_pension_calculations'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/calculations*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.calculations.index') }}">
                        <i class="icon im im-icon-Calculator"></i>
                        <i class="sidenav-mini-icon"> PC </i>
                        <span class="item-name">Calculations</span>
                    </a>
                </li>
            @endif
            @if(can('manage_pension_disbursements'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/disbursements*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.disbursements.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> PD </i>
                        <span class="item-name">Disbursements</span>
                    </a>
                </li>
            @endif
            @if(can('manage_arrear_bills'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/arrear-bills*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.arrear-bills.index') }}">
                        <i class="icon im im-icon-File-Clipboard"></i>
                        <i class="sidenav-mini-icon"> AB </i>
                        <span class="item-name">Arrear Bills</span>
                    </a>
                </li>
            @endif
            @if(can('view_pension_reports'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/pension/reports*') ? 'active' : '' !!}"
                        href="{{ route('admin.pension.reports.index') }}">
                        <i class="icon im im-icon-File-Chart"></i>
                        <i class="sidenav-mini-icon"> PR </i>
                        <span class="item-name">Pension Reports</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Recruitment --}}
@if(can('recruitment') && (can('manage_recruitment') || can('manage_recruitment_applications') || can('manage_recruitment_posts') || can('manage_career_page')))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('admin/recruitment/dashboard') || Request::is('recruitment*') || Request::is('recruitments*') || Request::is('admin/applications*') || Request::is('admin/career-page*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#recruitment_menu" role="button" aria-expanded="false"
            aria-controls="recruitment_menu">
            <i class="icon im im-icon-Business-Man"></i>
            <span class="item-name">Recruitment</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! Request::is('admin/recruitment/dashboard') || Request::is('recruitment*') || Request::is('recruitments*') || Request::is('admin/applications*') || Request::is('admin/career-page*') ? 'show' : '' !!}"
            id="recruitment_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_recruitment'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/recruitment/dashboard') ? 'active' : '' !!}"
                        href="{{ route('admin.recruitment.dashboard') }}"> 
                        <i class="icon im im-icon-Dashboard"></i>
                        <i class="sidenav-mini-icon"> D </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
            @endif
            @if(can('manage_recruitment_applications'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/applications*') ? 'active' : '' !!}"
                        href="{{ route('admin.applications.index') }}"> 
                        <i class="icon im im-icon-Student-MaleFemale"></i>
                        <i class="sidenav-mini-icon"> JA </i>
                        <span class="item-name">Applications</span>
                    </a>
                </li>
            @endif
            @if(can('manage_recruitment_posts'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('recruitments*') ? 'active' : '' !!}"
                        href="{{ route('recruitments.index') }}"> 
                        <i class="icon im im-icon-Business-Man"></i>
                        <i class="sidenav-mini-icon"> R </i>
                        <span class="item-name">Recruitment</span>
                    </a>
                </li>
            @endif
            @if(can('manage_career_page'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/career-page*') ? 'active' : '' !!}" href="{{ route('admin.career-page.index') }}"> 
                        <i class="icon im im-icon-Globe"></i>
                        <i class="sidenav-mini-icon"> CP </i>
                        <span class="item-name">Career Page Setup</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Biometric --}}
@if(can('biometric') && (can('manage_biometric_devices') || can('view_biometric_attendance_logs') || can('manage_biometric_employee_mappings') || can('manage_biometric_commands')))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('biometricDevices*') || Request::is('biometricAttendanceLogs*') || Request::is('biometricEmployeeMappings*') || Request::is('biometricCommands*') ? 'active' : '') !!}"
        data-bs-toggle="collapse" href="#biometric_menu" role="button" aria-expanded="false"
        aria-controls="biometric_menu">
        <i class="icon im im-icon-Fingerprint"></i>
        <span class="item-name">Biometric</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('biometricDevices*') || Request::is('biometricAttendanceLogs*') || Request::is('biometricEmployeeMappings*') || Request::is('biometricCommands*') ? 'show' : '') !!}"
        id="biometric_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_biometric_devices'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('biometricDevices*') ? 'active' : '' !!}"
                href="{{ route('biometricDevices.index') }}">
                <i class="icon im im-icon-Monitor"></i>
                <i class="sidenav-mini-icon"> D </i>
                <span class="item-name">Devices</span>
            </a>
        </li>
        @endif
        @if(can('view_biometric_attendance_logs'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('biometricAttendanceLogs*') ? 'active' : '' !!}"
                href="{{ route('biometricAttendanceLogs.index') }}">
                <i class="icon im im-icon-Calendar-4"></i>
                <i class="sidenav-mini-icon"> L </i>
                <span class="item-name">Attendance Logs</span>
            </a>
        </li>
        @endif
        @if(can('manage_biometric_employee_mappings'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('biometricEmployeeMappings*') ? 'active' : '' !!}"
                href="{{ route('biometricEmployeeMappings.index') }}">
                <i class="icon im im-icon-Link"></i>
                <i class="sidenav-mini-icon"> M </i>
                <span class="item-name">Employees Mapping</span>
            </a>
        </li>
        @endif
        @if(can('manage_biometric_commands'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('biometricCommands*') ? 'active' : '' !!}"
                href="{{ route('biometricCommands.index') }}">
                <i class="icon im im-icon-Code-Window"></i>
                <i class="sidenav-mini-icon"> C </i>
                <span class="item-name">Commands</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

{{-- Inventory --}}
@if(can('inventory') && (can('manage_asset_categories') || can('manage_assets') || can('manage_asset_assignments') || can('view_asset_logs') || can('view_inventory_reports') || can('maintenance')))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('admin/inventory*') || Request::is('admin/maintenance*') ? 'active' : '') !!}"
        data-bs-toggle="collapse" href="#inventory_menu" role="button" aria-expanded="false"
        aria-controls="inventory_menu">
        <i class="icon im im-icon-Box-withFolders"></i>
        <span class="item-name">Inventory</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! Request::is('admin/inventory*') || Request::is('admin/maintenance*') ? 'show' : '' !!}"
        id="inventory_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_asset_categories'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('admin/inventory/asset-categories*') ? 'active' : '' !!}"
                href="{{ route('admin.inventory.asset-categories.index') }}"> 
                <i class="icon im im-icon-Folder-Add"></i>
                <i class="sidenav-mini-icon"> AC </i>
                <span class="item-name">Asset Categories</span>
            </a>
        </li>
        @endif
        @if(can('manage_assets'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('admin/inventory/assets*') ? 'active' : '' !!}"
                href="{{ route('admin.inventory.assets.index') }}"> 
                <i class="icon im im-icon-Box-Full"></i>
                <i class="sidenav-mini-icon"> A </i>
                <span class="item-name">Assets Management</span>
            </a>
        </li>
        @endif
        @if(can('manage_asset_assignments'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('admin/inventory/asset-assignments*') ? 'active' : '' !!}"
                href="{{ route('admin.inventory.asset-assignments.index') }}"> 
                <i class="icon im im-icon-Checked-User"></i>
                <i class="sidenav-mini-icon"> AA </i>
                <span class="item-name">Asset Assignments</span>
            </a>
        </li>
        @endif
        @if(can('view_asset_logs'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('admin/inventory/asset-logs*') ? 'active' : '' !!}"
                href="{{ route('admin.inventory.asset-logs.index') }}"> 
                <i class="icon im im-icon-File-Search"></i>
                <i class="sidenav-mini-icon"> AL </i>
                <span class="item-name">Asset Audit Logs</span>
            </a>
        </li>
        @endif
        @if(can('view_inventory_reports'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('admin/inventory/reports*') ? 'active' : '' !!}"
                data-bs-toggle="collapse" href="#inventory_reports_menu" role="button" aria-expanded="false"
                aria-controls="inventory_reports_menu">
                <i class="icon im im-icon-File-Chart"></i>
                <span class="item-name">Inventory Reports</span>
                <i class="right-icon im im-icon-Arrow-Right"></i>
            </a>
            <ul class="sub-nav collapse {!! Request::is('admin/inventory/reports*') ? 'show' : '' !!}"
                id="inventory_reports_menu" style="padding-left: 15px; list-style-type: none;">
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/inventory/reports/assets*') ? 'active' : '' !!}"
                        href="{{ route('admin.inventory.reports.assets') }}"> 
                        <i class="icon im im-icon-File-Search"></i>
                        <i class="sidenav-mini-icon"> AR </i>
                        <span class="item-name">Asset Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/inventory/reports/assignments*') ? 'active' : '' !!}"
                        href="{{ route('admin.inventory.reports.assignments') }}"> 
                        <i class="icon im im-icon-Checked-User"></i>
                        <i class="sidenav-mini-icon"> ASR </i>
                        <span class="item-name">Assignment Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/inventory/reports/lifecycle*') ? 'active' : '' !!}"
                        href="{{ route('admin.inventory.reports.lifecycle') }}"> 
                        <i class="icon im im-icon-Time-Backup"></i>
                        <i class="sidenav-mini-icon"> LR </i>
                        <span class="item-name">Lifecycle Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/inventory/reports/inventory*') ? 'active' : '' !!}"
                        href="{{ route('admin.inventory.reports.inventory') }}"> 
                        <i class="icon im im-icon-Box-Full"></i>
                        <i class="sidenav-mini-icon"> IR </i>
                        <span class="item-name">General Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        {{-- Nested Maintenance Menu --}}
        @if(can('maintenance'))
        <li class="nav-item">
            <a class="nav-link {!! (Request::is('admin/maintenance*') ? 'active' : '') !!}"
                data-bs-toggle="collapse" href="#nested_maintenance_menu" role="button" aria-expanded="false"
                aria-controls="nested_maintenance_menu">
                <i class="icon im im-icon-Wrench"></i>
                <span class="item-name">Maintenance</span>
                <i class="right-icon im im-icon-Arrow-Right"></i>
            </a>
            <ul class="sub-nav collapse {!! Request::is('admin/maintenance*') ? 'show' : '' !!}"
                id="nested_maintenance_menu" style="padding-left: 15px; list-style-type: none;">
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/maintenance') ? 'active' : '' !!}"
                        href="{{ route('admin.maintenance.index') }}"> 
                        <i class="icon im im-icon-Dashboard"></i>
                        <i class="sidenav-mini-icon"> DB </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/maintenance/vendors*') ? 'active' : '' !!}"
                        href="{{ route('admin.maintenance.vendors.index') }}"> 
                        <i class="icon im im-icon-MaleFemale"></i>
                        <i class="sidenav-mini-icon"> MV </i>
                        <span class="item-name">Vendors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/maintenance/types*') ? 'active' : '' !!}"
                        href="{{ route('admin.maintenance.types.index') }}"> 
                        <i class="icon im im-icon-Tag"></i>
                        <i class="sidenav-mini-icon"> MT </i>
                        <span class="item-name">Maintenance Types</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/maintenance/requests*') ? 'active' : '' !!}"
                        href="{{ route('admin.maintenance.requests.index') }}"> 
                        <i class="icon im im-icon-Checked-User"></i>
                        <i class="sidenav-mini-icon"> MR </i>
                        <span class="item-name">Maintenance Requests</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('admin/maintenance/reports*') ? 'active' : '' !!}"
                        href="{{ route('admin.maintenance.reports.index') }}"> 
                        <i class="icon im im-icon-File-Chart"></i>
                        <i class="sidenav-mini-icon"> RP </i>
                        <span class="item-name">Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</li>
@endif

{{-- Settings --}}
@if(can('settings') && (can('manage_site_settings') || can('manage_roles_and_permissions') || can('manage_salaryGrades') || can('manage_allowance_settings') || can('bankSetups') || can('taxSetups') || can('notices')))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('siteSettings*') || Request::is('notices*') || Request::is('roleAndPermissions*') || Request::is('salaryGrades*') || Request::is('allowanceSettings*') || Request::is('bankSetups*') || Request::is('taxSetups*') ? 'active' : '') !!}"
            data-bs-toggle="collapse" href="#settings_menu" role="button" aria-expanded="false"
            aria-controls="settings_menu">
            <i class="icon im im-icon-Gear"></i>
            <span class="item-name">Settings</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! (Request::is('siteSettings*') || Request::is('notices*') || Request::is('roleAndPermissions*') || Request::is('salaryGrades*') || Request::is('allowanceSettings*') || Request::is('bankSetups*') || Request::is('taxSetups*') ? 'show' : '') !!}"
            id="settings_menu" data-bs-parent="#sidebar-menu">
            @if(can('manage_site_settings'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('siteSettings*') ? 'active' : '' !!}"
                        href="{{ route('siteSettings.index') }}">
                        <i class="icon im im-icon-Settings-Window"></i>
                        <i class="sidenav-mini-icon"> S </i>
                        <span class="item-name">Site Settings</span>
                    </a>
                </li>
            @endif

            @if(can('manage_roles_and_permissions'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('roleAndPermissions*') ? 'active' : '' !!}"
                        href="{{ route('roleAndPermissions.index') }}">
                        <i class="icon im im-icon-Security-Settings"></i>
                        <i class="sidenav-mini-icon"> R </i>
                        <span class="item-name">Role Management</span>
                    </a>
                </li>
            @endif
            @if(can('manage_salaryGrades'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('salaryGrades*') ? 'active' : '' !!}"
                        href="{{ route('salaryGrades.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> SG </i>
                        <span class="item-name">Salary Grades</span>
                    </a>
                </li>
            @endif
            @if(can('manage_allowance_settings'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('allowanceSettings*') ? 'active' : '' !!}"
                        href="{{ route('allowanceSettings.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> AS </i>
                        <span class="item-name">Allowance Settings</span>
                    </a>
                </li>
            @endif
            @if(can('bankSetups'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('bankSetups*') ? 'active' : '' !!}"
                        href="{{ route('bankSetups.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> BS </i>
                        <span class="item-name">Bank Setups</span>
                    </a>
                </li>
            @endif
            @if(can('taxSetups'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('taxSetups*') ? 'active' : '' !!}" href="{{ route('taxSetups.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> TS </i>
                        <span class="item-name">Tax Setups</span>
                    </a>
                </li>
            @endif
            @if(can('notices'))
                <li class="nav-item">
                    <a class="nav-link {!! Request::is('notices*') ? 'active' : '' !!}" href="{{ route('notices.index') }}">
                        <i class="icon im im-icon-Money-Bag"></i>
                        <i class="sidenav-mini-icon"> N </i>
                        <span class="item-name">Notices</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif

{{-- Logout --}}
<li class="nav-item" style="left: 15px; font-weight: bold;">
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="icon im im-icon-Security-Settings"></i>
        <span style="margin-left: 15px;" class="item-name">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
<br><br>