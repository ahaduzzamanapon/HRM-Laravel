{{-- Dashboard --}}
<li class="nav-item">
    <a class="nav-link {!! Request::is('/') ? 'active' : '' !!}" aria-current="page" href="{{ url('/') }}" >
        <i class="icon im im-icon-Home"></i>
        <span class="item-name">Dashboard</span>
    </a>
</li>

{{-- Users Management --}}
@if(can('user_management'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('users*')? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#users_menu" role="button" aria-expanded="false" aria-controls="users_menu">
        <i class="icon im im-icon-User"></i>
        <span class="item-name">Staff</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('users*') ? 'show' : '') !!}" id="users_menu" data-bs-parent="#sidebar-menu">
        @if(can('user'))
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





<li class="nav-item">
    <a class="nav-link {!! (Request::is('designations*') || Request::is('departments*') ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#organization_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">Organization</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('designations*') || Request::is('departments*') ? 'show' : ''  !!}" id="organization_menu" data-bs-parent="#sidebar-menu">
        @if(can('designations'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('designations*') ? 'active' : '' !!}" href="{{ route('designations.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> D </i>
                <span class="item-name">Designations</span>
            </a>
        </li>
        @endif
        @if(can('departments'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('departments*') ? 'active' : '' !!}" href="{{ route('departments.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> D </i>
                <span class="item-name">departments</span>
            </a>
        </li>
        @endif
    </ul>
</li>



<li class="nav-item">
    <a class="nav-link {!! (Request::is('holydays*')  ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#hr_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">HR</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse  {!!  Request::is('holydays*')  ? 'show' : ''  !!}" id="hr_menu" data-bs-parent="#sidebar-menu">
        @if(can('designations'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('holydays*') ? 'active' : '' !!}" href="{{ route('holydays.index') }}">
                <i class="icon im im-icon-Teacher"></i>
                <i class="sidenav-mini-icon"> H </i>
                <span class="item-name">Holyday Management</span>
            </a>
        </li>
        @endif
      
    </ul>
</li>




{{-- Settings --}}
@if(can('settings'))
<li class="nav-item">
    <a class="nav-link {!! (Request::is('siteSettings*') || Request::is('roleAndPermissions*') || Request::is('branches*')  ? 'active' : '' ) !!}" data-bs-toggle="collapse" href="#settings_menu" role="button" aria-expanded="false" aria-controls="settings_menu">
        <i class="icon im im-icon-Gear"></i>
        <span class="item-name">Settings</span>
        <i class="right-icon im im-icon-Arrow-Right"></i>
    </a>
    <ul class="sub-nav collapse {!! (Request::is('siteSettings*')  || Request::is('roleAndPermissions*') || Request::is('branches*')  ? 'show' : '' ) !!}" id="settings_menu" data-bs-parent="#sidebar-menu">
        @if(can('site_settings'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('siteSettings*') ? 'active' : '' !!}" href="{{ route('siteSettings.index') }}">
                <i class="icon im im-icon-Settings-Window"></i>
                <i class="sidenav-mini-icon"> S </i>
                <span class="item-name">Site Settings</span>
            </a>
        </li>
        @endif
        @if(can('branches'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('branches*') ? 'active' : '' !!}" href="{{ route('branches.index') }}">
                <i class="icon im im-icon-Security-Settings"></i>
                <i class="sidenav-mini-icon"> B </i>
                <span class="item-name">Branch Management</span>
            </a>
        </li>
        
        @endif
        @if(can('roll_and_permission'))
        <li class="nav-item">
            <a class="nav-link {!! Request::is('roleAndPermissions*') ? 'active' : '' !!}" href="{{ route('roleAndPermissions.index') }}">
                <i class="icon im im-icon-Security-Settings"></i>
                <i class="sidenav-mini-icon"> R </i>
                <span class="item-name">Role Management</span>
            </a>
        </li>
        
        @endif
    </ul>
</li>
@endif