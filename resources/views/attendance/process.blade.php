@extends('layouts.default')

@section('title') Attendance Process @parent @stop

@section('content')
<style>
    .attn-page { padding: 0 4px; }

    /* Header */
    .attn-header {
        background: linear-gradient(135deg, #1e3a5f 0%, #0177bc 100%);
        color: white; border-radius: 10px; padding: 18px 22px; margin-bottom: 16px;
        display: flex; align-items: center; justify-content: space-between;
    }
.attn-header h4 {
    margin: 0;
    font-weight: 700;
    font-size: 1.2rem;
    color: white;
}    .attn-header p  { margin: 3px 0 0; opacity: 0.8; font-size: 0.82rem; }

    /* Cards */
    .ap-card {
        background: #fff; border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08); border: none; margin-bottom: 16px;
    }
    .ap-card-body { padding: 18px 20px; }

    /* Form controls */
    .ap-label { font-size: 0.78rem; font-weight: 600; color: #546e7a; margin-bottom: 4px; display: block; }
    .ap-input {
        border-radius: 7px; border: 1.5px solid #dde3ec; font-size: 0.83rem;
        padding: 6px 10px; width: 100%; transition: border 0.2s;
    }
    .ap-input:focus { border-color: #0177bc; outline: none; box-shadow: 0 0 0 3px rgba(1,119,188,0.1); }

    /* Buttons */
    .btn-ap-primary {
        background: linear-gradient(135deg, #1e3a5f, #0177bc);
        color: white; border: none; border-radius: 7px;
        padding: 8px 16px; font-size: 0.82rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-ap-primary:hover { opacity: 0.9; transform: translateY(-1px); color: white; box-shadow: 0 4px 12px rgba(1,119,188,0.3); }
    .btn-ap-success {
        background: #e8f5e9; color: #2e7d32; border: 1.5px solid #a5d6a7;
        border-radius: 7px; padding: 8px 16px; font-size: 0.82rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-ap-success:hover { background: #c8e6c9; color: #1b5e20; }
    .btn-ap-warning {
        background: #fff8e1; color: #e65100; border: 1.5px solid #ffcc80;
        border-radius: 7px; padding: 8px 16px; font-size: 0.82rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-ap-warning:hover { background: #ffe0b2; }

    .btn-gap { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }

    /* Divider */
    .ap-divider { border: none; border-top: 1.5px solid #f0f4f8; margin: 14px 0; }

    /* Manual card */
    .manual-card {
        background: #f8faff; border: 1.5px dashed #90caf9;
        border-radius: 10px; padding: 16px; margin-top: 12px; display: none;
    }

    /* Tabs */
    .ap-tabs { border-bottom: 2px solid #e8eef5; margin-bottom: 14px; display: flex; }
    .ap-tab-btn {
        background: none; border: none; padding: 9px 18px; font-size: 0.84rem;
        font-weight: 600; color: #8e9aab; cursor: pointer; border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }
    .ap-tab-btn.active { color: #0177bc; background: #eef6ff; border-bottom: 2px solid #0177bc; margin-bottom: -2px; }
    .ap-tab-pane { display: none; }
    .ap-tab-pane.active { display: block; }

    /* Report filter buttons */
    .rpt-btn {
        border-radius: 20px; font-size: 0.78rem; font-weight: 700;
        padding: 5px 14px; border: 2px solid; cursor: pointer;
        background: none; transition: all 0.2s; margin: 3px;
    }
    .rpt-btn.t-all    { border-color: #0177bc; color: #0177bc; }
    .rpt-btn.t-all:hover, .rpt-btn.t-all.active  { background: #0177bc; color: white; }
    .rpt-btn.t-ok     { border-color: #2e7d32; color: #2e7d32; }
    .rpt-btn.t-ok:hover, .rpt-btn.t-ok.active    { background: #2e7d32; color: white; }
    .rpt-btn.t-no     { border-color: #c62828; color: #c62828; }
    .rpt-btn.t-no:hover, .rpt-btn.t-no.active    { background: #c62828; color: white; }
    .rpt-btn.t-late   { border-color: #e65100; color: #e65100; }
    .rpt-btn.t-late:hover, .rpt-btn.t-late.active { background: #e65100; color: white; }
    .rpt-btn.t-leave  { border-color: #6a1b9a; color: #6a1b9a; }
    .rpt-btn.t-leave:hover, .rpt-btn.t-leave.active { background: #6a1b9a; color: white; }

    /* Employee panel */
    .emp-panel {
        background: #fff; border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        display: flex; flex-direction: column;
        height: calc(100vh - 130px);
    }
    .emp-panel-head {
        padding: 12px 16px; border-bottom: 1.5px solid #f0f4f8;
        display: flex; align-items: center; justify-content: space-between;
    }
    .emp-panel-head h6 { margin: 0; font-weight: 700; color: #1e3a5f; font-size: 0.92rem; }
    .emp-count-badge {
        background: #0177bc; color: white; border-radius: 20px;
        padding: 2px 10px; font-size: 0.73rem; font-weight: 700;
    }
    .emp-search-wrap { padding: 10px 12px; border-bottom: 1.5px solid #f0f4f8; }
    .emp-search-wrap input {
        width: 100%; border: 1.5px solid #dde3ec; border-radius: 7px;
        padding: 6px 10px; font-size: 0.8rem; outline: none;
    }
    .emp-search-wrap input:focus { border-color: #0177bc; }
    .emp-sel-bar {
        padding: 7px 14px; border-bottom: 1.5px solid #f0f4f8;
        display: flex; align-items: center; justify-content: space-between;
    }
    .emp-sel-bar label { font-size: 0.78rem; font-weight: 600; color: #546e7a; margin: 0; display: flex; align-items: center; gap: 6px; cursor: pointer; }
    .sel-count { font-size: 0.76rem; color: #0177bc; font-weight: 700; }
    .emp-list { flex: 1; overflow-y: auto; padding: 6px 8px; }

    /* Employee item */
    .emp-item {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 10px; border-radius: 8px; cursor: pointer;
        transition: background 0.12s; margin-bottom: 2px;
    }
    .emp-item:hover { background: #eef6ff; }
    .emp-item.is-checked { background: #e3f0fc; }
    .emp-avatar {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #0177bc, #1e3a5f);
        color: white; display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
    }
    .emp-info-name { font-size: 0.82rem; font-weight: 600; color: #263238; }
    .emp-info-id   { font-size: 0.72rem; color: #90a4ae; }
    .emp-cb { width: 15px; height: 15px; flex-shrink: 0; accent-color: #0177bc; cursor: pointer; }
    .emp-no-data { text-align: center; padding: 30px 10px; color: #aaa; font-size: 0.83rem; }
</style>

<div class="attn-page">

    <div class="mb-3">
        <h4 style="font-weight: 700; color: #1e3a5f; margin: 0;">Attendance Process</h4>
    </div>

    <div class="row">
        {{-- LEFT PANEL --}}
        <div class="col-md-8">

            {{-- Filters --}}
            <div class="ap-card">
                <div class="ap-card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <label class="ap-label">From Date</label>
                            <input type="date" id="from_date" class="ap-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-sm-4">
                            <label class="ap-label">To Date <small style="color:#90a4ae">(range)</small></label>
                            <input type="date" id="to_date" class="ap-input">
                        </div>
                        <div class="col-sm-4">
                            <label class="ap-label">Status</label>
                            <select id="status" class="ap-input">
                                <option value="regular" selected>Regular</option>
                                <option value="left">Left</option>
                                <option value="resign">Resign</option>
                                <option value="retired">Retired</option>
                                <option value="terminate">Terminate</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="ap-label">Branch</label>
                            <select id="branch_id" class="ap-input">
                                <option value="">Select...</option>
                                @foreach($branches as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="ap-label">Department</label>
                            <select id="department_id" class="ap-input">
                                <option value="">Select...</option>
                                @foreach($departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="ap-label">Designation</label>
                            <select id="designation_id" class="ap-input">
                                <option value="">Select...</option>
                                @foreach($designations as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="ap-divider">

                    <div class="btn-gap">
                        <button class="btn-ap-primary" id="btn-process">
                            <i class="fa fa-play mr-1"></i> Process Single Date
                        </button>
                        <button class="btn-ap-success" id="btn-process-range">
                            <i class="fa fa-calendar mr-1"></i> Process Date Range
                        </button>
                        <button class="btn-ap-warning" id="btn-manual">
                            <i class="fa fa-pencil mr-1"></i> Manual Attendance
                        </button>
                    </div>

                    <div class="manual-card" id="manual-card">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="ap-label">Clock In</label>
                                <input type="time" id="manual_clock_in" class="ap-input">
                            </div>
                            <div class="col-sm-4">
                                <label class="ap-label">Clock Out</label>
                                <input type="time" id="manual_clock_out" class="ap-input">
                            </div>
                            <div class="col-sm-4" style="display:flex;align-items:flex-end;">
                                <button class="btn-ap-primary" style="width:100%;justify-content:center;" id="btn-save-manual">
                                    <i class="fa fa-save mr-1"></i> Save Manual
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Tabs --}}
            <div class="ap-card">
                <div class="ap-card-body">
                    <div class="ap-tabs">
                        <button class="ap-tab-btn active" data-target="tab-daily">
                            <i class="fa fa-bar-chart mr-1"></i> Daily Report
                        </button>
                        <button class="ap-tab-btn" data-target="tab-other">
                            <i class="fa fa-file-text mr-1"></i> Other Reports
                        </button>
                    </div>

                    <div class="ap-tab-pane active" id="tab-daily">
                        <button class="rpt-btn t-all filter-btn" data-filter="all"><i class="fa fa-list mr-1"></i> All</button>
                        <button class="rpt-btn t-ok filter-btn" data-filter="present"><i class="fa fa-check mr-1"></i> Present</button>
                        <button class="rpt-btn t-no filter-btn" data-filter="absent"><i class="fa fa-times mr-1"></i> Absent</button>
                        <button class="rpt-btn t-late filter-btn" data-filter="late"><i class="fa fa-clock-o mr-1"></i> Late</button>
                        <button class="rpt-btn t-leave filter-btn" data-filter="leave"><i class="fa fa-sign-out mr-1"></i> Leave</button>
                    </div>

                    <div class="ap-tab-pane" id="tab-other">
                        <button class="rpt-btn t-all filter-btn" data-filter="job_card"><i class="fa fa-id-card mr-1"></i> Job Card</button>
                        <button class="rpt-btn t-ok filter-btn" data-filter="general_report"><i class="fa fa-users mr-1"></i> General Report</button>
                        <button class="rpt-btn t-ok filter-btn" data-filter="intime_only" style="border-color:#00838f;color:#00838f;" onmouseover="this.style.background='#00838f';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='#00838f'"><i class="fa fa-sign-in mr-1"></i> In-Time Only</button>
                        <button class="rpt-btn t-late filter-btn" data-filter="outtime_only" style="border-color:#6d4c41;color:#6d4c41;" onmouseover="this.style.background='#6d4c41';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='#6d4c41'"><i class="fa fa-sign-out mr-1"></i> Out-Time Only</button>
                        <button class="rpt-btn t-leave filter-btn" data-filter="branch_wise" style="border-color:#1565c0;color:#1565c0;" onmouseover="this.style.background='#1565c0';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='#1565c0'"><i class="fa fa-building mr-1"></i> Branch Wise</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="col-md-4">
            <div class="emp-panel">
                <div class="emp-panel-head">
                    <h6><i class="fa fa-users mr-1"></i> Employee List</h6>
                    <span class="emp-count-badge" id="emp-count">{{ $users->count() }}</span>
                </div>
                <div class="emp-search-wrap">
                    <input type="text" id="emp-search" placeholder="&#xf002; Search by name or ID...">
                </div>
                <div class="emp-sel-bar">
                    <label>
                        <input type="checkbox" id="select-all" class="emp-cb"> Select All
                    </label>
                    <span class="sel-count" id="sel-count">0 selected</span>
                </div>
                <div class="emp-list" id="emp-list">
                    @foreach($users as $user)
                    @php
                        $fn = trim(($user->name ?? '') . ' ' . ($user->last_name ?? ''));
                        $empIdStr = $user->emp_id ? " ({$user->emp_id})" : '';
                    @endphp
                    <div class="emp-item" data-id="{{ $user->id }}" data-name="{{ strtolower($fn . ' ' . ($user->emp_id ?? '')) }}">
                        <input type="checkbox" class="emp-cb user-checkbox" value="{{ $user->id }}">
                        <div class="emp-info-name">{{ $fn }} <span class="text-muted ms-1" style="font-size: 0.75rem; font-weight: normal;">{{ $empIdStr }}</span></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {

    /* ---- Tabs ---- */
    $('.ap-tab-btn').on('click', function() {
        $('.ap-tab-btn').removeClass('active');
        $('.ap-tab-pane').removeClass('active');
        $(this).addClass('active');
        $('#' + $(this).data('target')).addClass('active');
    });

    /* ---- Employee item click ---- */
    $(document).on('click', '.emp-item', function(e) {
        if ($(e.target).hasClass('emp-cb')) return;
        var cb = $(this).find('.user-checkbox');
        cb.prop('checked', !cb.prop('checked'));
        updateSelected();
    });
    $(document).on('change', '.user-checkbox', updateSelected);

    function updateSelected() {
        var cnt = $('.user-checkbox:checked').length;
        $('#sel-count').text(cnt + ' selected');
        $('.emp-item').each(function() {
            $(this).toggleClass('is-checked', $(this).find('.user-checkbox').prop('checked'));
        });
    }

    /* ---- Select All ---- */
    $('#select-all').on('change', function() {
        $('.emp-list .user-checkbox').prop('checked', $(this).prop('checked'));
        updateSelected();
    });

    /* ---- Search ---- */
    $('#emp-search').on('input', function() {
        var q = $(this).val().toLowerCase();
        $('#emp-list .emp-item').each(function() {
            var match = $(this).data('name').includes(q);
            $(this).toggle(match);
        });
    });

    /* ---- Filter dropdowns ---- */
    $('#branch_id, #department_id, #designation_id, #status').on('change', function() {
        $.ajax({
            type: 'GET',
            url: '{{ route("attendance.filter") }}',
            data: {
                branch_id: $('#branch_id').val(),
                department_id: $('#department_id').val(),
                designation_id: $('#designation_id').val(),
                status: $('#status').val()
            },
            success: function(users) {
                var list = $('#emp-list');
                list.empty();
                if (!users.length) {
                    list.html('<div class="emp-no-data"><i class="fa fa-inbox fa-2x" style="display:block;margin-bottom:8px;"></i>No employees found</div>');
                    $('#emp-count').text(0); return;
                }
                users.forEach(function(u) {
                    var fn = $.trim((u.name || '') + ' ' + (u.last_name || ''));
                    var empIdStr = u.emp_id ? ' (' + u.emp_id + ')' : '';
                    list.append(
                        '<div class="emp-item" data-id="'+u.id+'" data-name="'+(fn + ' ' + (u.emp_id || '')).toLowerCase()+'">' +
                        '<input type="checkbox" class="emp-cb user-checkbox" value="'+u.id+'">' +
                        '<div class="emp-info-name">'+fn+' <span class="text-muted ms-1" style="font-size: 0.75rem; font-weight: normal;">'+empIdStr+'</span></div>' +
                        '</div>'
                    );
                });
                $('#emp-count').text(users.length);
                updateSelected();
            }
        });
    });

    /* ---- Manual toggle ---- */
    $('#btn-manual').on('click', function() { $('#manual-card').slideToggle(200); });

    /* ---- Get selected IDs ---- */
    function getIds() {
        return $('.user-checkbox:checked').map(function(){ return $(this).val(); }).get();
    }

    /* ---- Process Single Date ---- */
    $('#btn-process').on('click', function() {
        var ids = getIds();
        if (!ids.length) { Swal.fire('Warning','Please select at least one employee.','warning'); return; }
        $.ajax({
            type: 'POST',
            url: '{{ route("attendance.process.store") }}',
            data: { from_date: $('#from_date').val(), users: ids, _token: '{{ csrf_token() }}' },
            success: function(r) {
                Swal.fire({ icon: r.success ? 'success' : 'warning', title: r.success ? 'Done!' : 'Note', text: r.message });
            },
            error: function(x) { Swal.fire('Error','Server error: '+x.status,'error'); }
        });
    });

    /* ---- Process Date Range ---- */
    $('#btn-process-range').on('click', function() {
        var ids = getIds();
        if (!ids.length) { Swal.fire('Warning','Please select at least one employee.','warning'); return; }
        var from = new Date($('#from_date').val()), to = new Date($('#to_date').val());
        if (!$('#to_date').val() || to < from) { Swal.fire('Warning','Please set a valid To Date.','warning'); return; }
        var dates = [];
        for (var d = new Date(from); d <= to; d.setDate(d.getDate()+1)) dates.push(new Date(d).toISOString().slice(0,10));
        var i = 0;
        function next() {
            if (i >= dates.length) { Swal.fire('Done!','Processed '+dates.length+' days.','success'); return; }
            $.ajax({
                type: 'POST',
                url: '{{ route("attendance.process.store") }}',
                data: { from_date: dates[i], users: ids, _token: '{{ csrf_token() }}' },
                success: function(){ i++; next(); },
                error: function(x){ Swal.fire('Error','Failed on '+dates[i]+': '+x.status,'error'); }
            });
        }
        next();
    });

    /* ---- Save Manual ---- */
    $('#btn-save-manual').on('click', function() {
        var ids = getIds();
        if (!ids.length) { Swal.fire('Warning','Please select at least one employee.','warning'); return; }
        $.ajax({
            type: 'POST',
            url: '{{ route("attendance.manual.store") }}',
            data: { users: ids, date: $('#from_date').val(), clock_in: $('#manual_clock_in').val(), clock_out: $('#manual_clock_out').val(), _token: '{{ csrf_token() }}' },
            success: function(r){ Swal.fire({ icon: r.success?'success':'error', title: r.success?'Saved!':'Error', text: r.message }); },
            error: function(){ Swal.fire('Error','Server error','error'); }
        });
    });

    /* ---- Report buttons ---- */
    $('.filter-btn').on('click', function() {
        var pane     = $(this).closest('.ap-tab-pane').attr('id');
        var tabId    = pane === 'tab-daily' ? 'daily' : 'other';
        var ids      = getIds();
        if (!ids.length) { Swal.fire('Warning','Please select at least one employee.','warning'); return; }

        let form = $('<form>', {
            action: "{{ route('attendance.report') }}",
            method: 'POST',
            target: '_blank'
        });

        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: 'report_type', value: tabId }));
        form.append($('<input>', { type: 'hidden', name: 'filter_type', value: $(this).data('filter') }));
        form.append($('<input>', { type: 'hidden', name: 'from_date', value: $('#from_date').val() }));
        form.append($('<input>', { type: 'hidden', name: 'to_date', value: $('#to_date').val() }));

        ids.forEach(function(id) {
            form.append($('<input>', { type: 'hidden', name: 'user_ids[]', value: id }));
        });

        $('body').append(form);
        form.submit();
        form.remove();
    });
});
</script>
@endpush
@endsection
