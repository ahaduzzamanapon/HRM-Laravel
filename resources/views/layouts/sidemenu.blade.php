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
        <img src="{{ asset(Auth::user()->image ?? 'assets/images/avatars/01.png') }}" alt="User Image" class="img-fluid rounded-circle mb-2" style="width: 60px;height: 60px;bject-fit: cover;">
        <h5 style="color: white;" class="mb-0">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h5>
        <p  style="color: white;"class="mb-0">{{ Auth::user()->email }}</p>
        <p style="color: white;" class="mb-0"><small>{{ Auth::user()->role->name ?? 'N/A' }}</small></p>
    </div>
@endauth

{{-- Dashboard --}}
<li class="nav-item">
    <a class="nav-link {!! Request::is('/') ? 'active' : '' !!}" aria-current="page" href="{{ url('/') }}" >
        <i class="icon im im-icon-Home"></i>
        <span class="item-name">Dashboard</span>
    </a>
</li>

{{-- Users Management --}}
@if(can('staff_management'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('users*')? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#users_menu" role="button" aria-expanded="false" aria-controls="users_menu">
        <i class="icon im im-icon-User"></i>
        <span class="item-name">Staff</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('users*') ? 'show' : '') !!}" id="users_menu" data-bs-parent="#sidebar-menu">
        @if(can('view_employees'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('users*') ? 'active' : '' !!}" href="{{ route('users.index') }}">
                <i class="icon im im-icon-User"></i>
                <i class="sidenav-mini-icon"> U </i>
                <span class="item-name">Employees</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif





@if(can('organization'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('designations*') || Request::is('departments*') || Request::is('branches*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#organization_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">Organization</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('designations*') || Request::is('departments*') || Request::is('branches*') ? 'show' : ''  !!}" id="organization_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_designations'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('designations*') ? 'active' : '' !!}" href="{{ route('designations.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> D </i>
                <span class="item-name">Designations</span>
            </a>
        </li>
        @endif
        @if(can('manage_departments'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('departments*') ? 'active' : '' !!}" href="{{ route('departments.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> D </i>
                <span class="item-name">Departments</span>
            </a>
        </li>
        @endif
        @if(can('rewardings'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('rewardings*') ? 'active' : '' !!}" href="{{ route('rewardings.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> R </i>
                <span class="item-name">Rewarding</span>
            </a>
        </li>
        @endif
        @if(can('innovations'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('innovations*') ? 'active' : '' !!}" href="{{ route('innovations.index') }}">
                <i class="icon im im-icon-Idea"></i>
                <i class="sidenav-mini-icon"> I </i>
                <span class="item-name">Innovations</span>
            </a>
        </li>
        @endif
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
<li class="nav-item">
    <a class="nav-link {!! (Request::is('holydays*') || Request::is('shifts*') || Request::is('attendanceFileUploads*') || Request::is('leaveTypes*') || Request::is('leaveApplications*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#hr_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">HR</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('holydays*') || Request::is('shifts*') || Request::is('attendanceFileUploads*') || Request::is('leaveTypes*') || Request::is('leaveApplications*') ? 'show' : ''  !!}" id="hr_menu" data-bs-parent="#sidebar-menu">
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
        @if(can('upload_attendance_files'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('attendanceFileUploads*') ? 'active' : '' !!}" href="{{ route('attendanceFileUploads.index') }}">
                <i class="icon im im-icon-Upload-toCloud"></i>
                <i class="sidenav-mini-icon"> AF </i>
                <span class="item-name">Attendance File Upload</span>
            </a>
        </li>
        @endif
        @if(can('manage_leave_types'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('leaveTypes*') ? 'active' : '' !!}" href="{{ route('leaveTypes.index') }}">
                <i class="icon im im-icon-Calendar-4"></i>
                <i class="sidenav-mini-icon"> LT </i>
                <span class="item-name">Leave Types</span>
            </a>
        </li>
        @endif
        @if(can('leave_applications'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('leaveApplications*') ? 'active' : '' !!}" href="{{ route('leaveApplications.index') }}">
                <i class="icon im im-icon-Calendar-4"></i>
                <i class="sidenav-mini-icon"> LA </i>
                <span class="item-name">Leave Applications</span>
            </a>
        </li>
        @endif
        @if(can('movements'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('movements*') ? 'active' : '' !!}" href="{{ route('movements.index') }}">
                <i class="icon im im-icon-Location-2"></i>
                <i class="sidenav-mini-icon"> M </i>
                <span class="item-name">Movements</span>
            </a>
        </li>
        @endif
        @if(can('process_attendance'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('attendance/process*') ? 'active' : '' !!}" href="{{ route('attendance.process.index') }}">
                <i class="icon im im-icon-Clock-Forward"></i>
                <i class="sidenav-mini-icon"> AP </i>
                <span class="item-name">Attendance Process</span>
            </a>
        </li>
        @endif
    </ul>
</li>

{{-- Payroll --}}
@if(can('payroll'))
    <li class="nav-item">
        <a class="nav-link {!! (Request::is('payroll*')? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#payroll_menu" role="button" aria-expanded="false" aria-controls="payroll_menu">
            <i class="icon im im-icon-User"></i>
            <span class="item-name">Payroll</span>
            <i class="right-icon im im-icon-Arrow-Right"></i>
        </a>
        <ul class="sub-nav collapse {!! (Request::is('payroll*') ? 'show' : '') !!}" id="payroll_menu" data-bs-parent="#sidebar-menu">
            @if(can('view_employees'))
            <li class="nav-item">
                <a class="nav-link {!! Request::is('payroll*') ? 'active' : '' !!}" href="{{ route('payroll.index') }}">
                    <i class="icon im im-icon-Clock-Forward"></i>
                    <i class="sidenav-mini-icon"> P </i>
                    <span class="item-name">Payroll Process</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
@endif

@if(can('welfare_fund'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('employeeChildrenEducationSupports*') || Request::is('funeralSupports*') || Request::is('medicalSupports*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#welfare_fund_menu" role="button" aria-expanded="false" aria-controls="welfare_fund_menu">
        <i class="icon im im-icon-Heart"></i>
        <span class="item-name">Welfare Fund</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('employeeChildrenEducationSupports*') || Request::is('funeralSupports*') || Request::is('medicalSupports*') ? 'show' : ''  !!}" id="welfare_fund_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_employee_children_education_supports'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('employeeChildrenEducationSupports*') ? 'active' : '' !!}" href="{{ route('employeeChildrenEducationSupports.index') }}">
                <i class="icon im im-icon-Student-Female"></i>
                <i class="sidenav-mini-icon"> ECES </i>
                <span class="item-name">Children Education Support</span>
            </a>
        </li>
        @endif
        @if(can('manage_funeral_supports'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('funeralSupports*') ? 'active' : '' !!}" href="{{ route('funeralSupports.index') }}">
                <i class="icon im im-icon-Coffin"></i>
                <i class="sidenav-mini-icon"> FS </i>
                <span class="item-name">Funeral Support</span>
            </a>
        </li>
        @endif
        @if(can('manage_medical_supports'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('medicalSupports*') ? 'active' : '' !!}" href="{{ route('medicalSupports.index') }}">
                <i class="icon im im-icon-Medical-Sign"></i>
                <i class="sidenav-mini-icon"> MS </i>
                <span class="item-name">Medical Support</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(can('disciplinary_actions'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('departmentalCases*') || Request::is('penalties*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#disciplinary_actions_menu" role="button" aria-expanded="false" aria-controls="disciplinary_actions_menu">
        <i class="icon im im-icon-Hammer"></i>
        <span class="item-name">Disciplinary Actions</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('departmentalCases*') || Request::is('penalties*') ? 'show' : ''  !!}" id="disciplinary_actions_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_departmental_cases'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('departmentalCases*') ? 'active' : '' !!}" href="{{ route('departmentalCases.index') }}">
                <i class="icon im im-icon-Folder-Open"></i>
                <i class="sidenav-mini-icon"> DC </i>
                <span class="item-name">Departmental Cases</span>
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

@if(can('loans_and_advances'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('loanTypes*') || Request::is('loans*') || Request::is('loanRepayments*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#loans_and_advances_menu" role="button" aria-expanded="false" aria-controls="loans_and_advances_menu">
        <i class="icon im im-icon-Money-Bag"></i>
        <span class="item-name">Loans and Advances</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('loanTypes*') || Request::is('loans*') || Request::is('loanRepayments*') ? 'show' : ''  !!}" id="loans_and_advances_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_loan_types'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('loanTypes*') ? 'active' : '' !!}" href="{{ route('loanTypes.index') }}">
                <i class="icon im im-icon-Align-Justify-All"></i>
                <i class="sidenav-mini-icon"> LT </i>
                <span class="item-name">Loan Types</span>
            </a>
        </li>
        @endif
        @if(can('manage_loans'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('loans*') ? 'active' : '' !!}" href="{{ route('loans.index') }}">
                <i class="icon im im-icon-File-Edit"></i>
                <i class="sidenav-mini-icon"> LA </i>
                <span class="item-name">Loan Applications</span>
            </a>
        </li>
        @endif
        @if(can('manage_loan_repayments'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('loanRepayments*') ? 'active' : '' !!}" href="{{ route('loanRepayments.index') }}">
                <i class="icon im im-icon-Money-Graph"></i>
                <i class="sidenav-mini-icon"> LR </i>
                <span class="item-name">Loan Repayments</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(can('provident_fund'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('providentFundSettings*') || Request::is('providentFunds*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#provident_fund_menu" role="button" aria-expanded="false" aria-controls="provident_fund_menu">
        <i class="icon im im-icon-Safe-Box"></i>
        <span class="item-name">Provident Fund</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('providentFundSettings*') || Request::is('providentFunds*') ? 'show' : ''  !!}" id="provident_fund_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_provident_fund_settings'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('providentFundSettings*') ? 'active' : '' !!}" href="{{ route('providentFundSettings.index') }}">
                <i class="icon im im-icon-Gear"></i>
                <i class="sidenav-mini-icon"> S </i>
                <span class="item-name">Settings</span>
            </a>
        </li>
        @endif
        @if(can('view_provident_fund_statements'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('providentFunds*') ? 'active' : '' !!}" href="{{ route('providentFunds.index') }}">
                <i class="icon im im-icon-File-Chart"></i>
                <i class="sidenav-mini-icon"> S </i>
                <span class="item-name">Statements</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

{{-- Settings --}}
@if(can('settings'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('siteSettings*') || Request::is('notices*') || Request::is('roleAndPermissions*')  ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#settings_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">Settings</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('siteSettings*') || Request::is('notices*') || Request::is('roleAndPermissions*')   ? 'show' : '' ) !!}" id="settings_menu" data-bs-parent="#sidebar-menu">
        @if(can('manage_site_settings'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('siteSettings*') ? 'active' : '' !!}" href="{{ route('siteSettings.index') }}">
                <i class="icon im im-icon-Settings-Window"></i>
                <i class="sidenav-mini-icon"> S </i>
                <span class="item-name">Site Settings</span>
            </a>
        </li>
        @endif

        @if(can('manage_roles_and_permissions'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('roleAndPermissions*') ? 'active' : '' !!}" href="{{ route('roleAndPermissions.index') }}">
                <i class="icon im im-icon-Security-Settings"></i>
                <i class="sidenav-mini-icon"> R </i>
                <span class="item-name">Role Management</span>
            </a>
        </li>
        @endif
        @if(can('manage_allowance_settings'))
        {{-- Allowance Settings --}}
        <li class="nav-item">
            <a class="nav-link {!! Request::is('allowanceSettings*') ? 'active' : '' !!}" href="{{ route('allowanceSettings.index') }}">
                <i class="icon im im-icon-Money-Bag"></i>
                <i class="sidenav-mini-icon"> AS </i>
                <span class="item-name">Allowance Settings</span>
            </a>
        </li>
        @endif
        @if(can('notices'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('notices*') ? 'active' : '' !!}" href="{{ route('notices.index') }}">
                <i class="icon im im-icon-Money-Bag"></i>
                <i class="sidenav-mini-icon"> N </i>
                <span class="item-name">notices</span>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif
