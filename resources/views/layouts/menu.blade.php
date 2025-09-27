<li class="{!! (Request::is('salaryGrades*') ? 'active' : '' ) !!}">
    <a href="{{ route('salaryGrades.index') }}">
        <span class="mm-text ">Salary Grades</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('bankSetups*') ? 'active' : '' ) !!}">
    <a href="{{ route('bankSetups.index') }}">
        <span class="mm-text ">Bank Setups</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

<li class="{!! (Request::is('taxSetups*') ? 'active' : '' ) !!}">
    <a href="{{ route('taxSetups.index') }}">
        <span class="mm-text ">Tax Setups</span>
        <span class="menu-icon"><i class="im im-icon-Structure"></i></span>
    </a>
</li>

