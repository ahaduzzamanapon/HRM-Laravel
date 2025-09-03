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
                <i class="icon im im-icon-File-Edit"></i>
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
        </ul>
</li>




{{-- Settings --}}
@if(can('settings'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('siteSettings*') || Request::is('roleAndPermissions*')  ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#settings_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">Settings</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('siteSettings*')  || Request::is('roleAndPermissions*')   ? 'show' : '' ) !!}" id="settings_menu" data-bs-parent="#sidebar-menu">
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
    </ul>
</li>
@endif