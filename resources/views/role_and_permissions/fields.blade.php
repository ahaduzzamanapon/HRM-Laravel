@php
    $isEditMode = isset($roleAndPermission) && !empty($roleAndPermission->id);
    $currentRoleId = $isEditMode ? $roleAndPermission->id : null;
@endphp

<!-- Basic Info Section -->
<div class="row g-3 mb-4">
    <!-- Name Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name', 'Role Name:', ['class'=>'form-label fw-bold text-dark']) !!}
            {!! Form::text('name', null, ['class' => 'form-control form-control-lg', 'id' => 'role_name_input', 'placeholder' => 'e.g. HR Manager', 'required']) !!}
            <small class="text-muted">Descriptive title for this role.</small>
        </div>
    </div>
    <!-- Key Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('key', 'Role System Key:', ['class'=>'form-label fw-bold text-dark']) !!}
            {!! Form::text('key', null, ['class' => 'form-control form-control-lg font-monospace', 'id' => 'role_key_input', 'placeholder' => 'e.g. hr_manager', 'required']) !!}
            <small class="text-muted">Unique key used in code & permissions check.</small>
        </div>
    </div>
    <!-- Branch Scope Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('branch_id', 'Branch Scope:', ['class'=>'form-label fw-bold text-dark']) !!}
            @if(isSuperAdmin())
                {!! Form::select('branch_id', ['' => 'All Branches'] + (isset($branches) ? $branches->toArray() : []), null, ['class' => 'form-select form-select-lg', 'id' => 'role_branch_select']) !!}
                <small class="text-muted">Super Admin can assign this role to any branch or make it global.</small>
            @else
                @php
                    if ($isEditMode && $roleAndPermission->branch) {
                        $displayBranchName = $roleAndPermission->branch->branch_name;
                        $assignedBranchId = $roleAndPermission->branch_id;
                    } else {
                        $userBranch = auth()->user()->branch;
                        if ($userBranch) {
                            $displayBranchName = $userBranch->branch_name;
                            $assignedBranchId = $userBranch->id;
                        } else {
                            $myBranchId = userBranchId();
                            $bObj = $myBranchId ? \App\Models\Branch::find($myBranchId) : null;
                            $displayBranchName = $bObj ? $bObj->branch_name : 'All Branches';
                            $assignedBranchId = $myBranchId;
                        }
                    }
                @endphp
                <input type="text" class="form-control form-control-lg bg-light text-dark fw-bold" value="{{ $displayBranchName }}" readonly>
                <input type="hidden" name="branch_id" value="{{ $assignedBranchId }}">
                <small class="text-muted d-block mt-1"><i class="im im-icon-Lock me-1 text-warning"></i> Role is automatically assigned to your branch.</small>
            @endif
        </div>
    </div>
</div>

<hr class="my-4 text-muted">

<!-- Permissions Section Header & Controls -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold text-primary mb-1">
            <i class="im im-icon-Key me-2"></i> Permissions Assignment Tree
        </h5>
        <p class="text-muted small mb-0">Select the permissions and sub-permissions assigned to this role.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        @if($isEditMode)
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6 shadow-sm align-items-center" id="realtime_status_badge" style="display: none; transition: all 0.3s ease;">
                <span id="realtime_status_text"></span>
            </span>
        @endif
        <span class="badge bg-primary fs-6 px-3 py-2" id="total_permissions_counter">
            Assigned Permissions: <span id="selected_perm_count">0</span> / {{ $totalPermissions ?? 0 }}
        </span>
        <button type="button" class="btn btn-outline-success btn-sm" id="btn_select_all">
            <i class="im im-icon-CheckMark me-1"></i> Select All
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm" id="btn_deselect_all">
            <i class="im im-icon-Close me-1"></i> Deselect All
        </button>
    </div>
</div>

<!-- Redesigned Search Box -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-5">
        <div class="search-box-wrapper shadow-sm rounded-pill border bg-white d-flex align-items-center px-3 py-1" style="transition: all 0.2s ease-in-out;">
            <i class="im im-icon-Magnifi-Glass fs-5 text-primary me-2"></i>
            <input type="text" id="permission_search_input" class="form-control border-0 shadow-none py-1 ps-0 text-dark fw-semibold" placeholder="Search permissions by name..." autocomplete="off" style="font-size: 0.95rem;">
            <button class="btn btn-sm btn-link text-muted text-decoration-none p-0 ms-2" type="button" id="btn_clear_search" style="display:none;">
                <i class="im im-icon-Close fs-6"></i>
            </button>
        </div>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div id="permissionsContainer">
        @include('role_and_permissions.partials.permission_tree', ["permissionTree" => $permissionTree, "permission_have" => $permission_have])
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-end align-items-center border-top pt-3 flex-wrap gap-2">
    <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-outline-secondary px-4">
        <i class="im im-icon-Arrow-Left me-1"></i> Back to Role List
    </a>
    @if(!$isEditMode)
        <button type="submit" class="btn btn-primary px-4 shadow-sm">
            <i class="im im-icon-Plus me-1"></i> Create Role
        </button>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            const isEditMode = @json($isEditMode);
            const currentRoleId = @json($currentRoleId);
            let isKeyManuallyEdited = false;
            let autoSaveTimer = null;

            function triggerRealtimeSync() {
                if (!isEditMode || !currentRoleId) return;

                clearTimeout(autoSaveTimer);
                
                $('#realtime_status_badge').stop(true, true).show().removeClass('bg-success-subtle text-success bg-danger-subtle text-danger').addClass('bg-warning-subtle text-warning');
                $('#realtime_status_text').html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

                autoSaveTimer = setTimeout(function() {
                    const checkedPermissionIds = $('input[name="permission[]"]:checked').map(function() {
                        return $(this).val();
                    }).get();

                    const roleName = $('#role_name_input').val();
                    const roleKey = $('#role_key_input').val();
                    const branchId = $('#role_branch_select').length ? $('#role_branch_select').val() : $('input[name="branch_id"]').val();

                    $.ajax({
                        url: "{{ $isEditMode ? route('roleAndPermissions.sync', $currentRoleId) : '' }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            name: roleName,
                            key: roleKey,
                            branch_id: branchId,
                            permission: checkedPermissionIds
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $('#realtime_status_badge').removeClass('bg-warning-subtle text-warning bg-danger-subtle text-danger').addClass('bg-success-subtle text-success');
                                $('#realtime_status_text').html('<i class="im im-icon-CheckMark me-1 text-success"></i> Saved');
                                setTimeout(function() {
                                    $('#realtime_status_badge').fadeOut();
                                }, 1200);
                            }
                        },
                        error: function(xhr) {
                            console.error('Real-time save failed:', xhr);
                            $('#realtime_status_badge').removeClass('bg-warning-subtle text-warning bg-success-subtle text-success').addClass('bg-danger-subtle text-danger');
                            $('#realtime_status_text').html('<i class="im im-icon-Close me-1 text-danger"></i> Save Failed');
                        }
                    });
                }, 200);
            }

            $('#role_key_input').on('keyup input change', function () {
                isKeyManuallyEdited = $(this).val().trim().length > 0;
                triggerRealtimeSync();
            });

            $('#role_name_input').on('keyup input change', function () {
                if (!isKeyManuallyEdited) {
                    const slug = $(this).val()
                        .toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '_')
                        .replace(/^-+|-+$/g, '');
                    $('#role_key_input').val(slug);
                }
                triggerRealtimeSync();
            });

            $('#role_branch_select').on('change', function() {
                triggerRealtimeSync();
            });

            function updateCounters() {
                const totalCheckboxes = $('input[name="permission[]"]:checked').length;
                $('#selected_perm_count').text(totalCheckboxes);

                $('.permission-module-card').each(function () {
                    const moduleId = $(this).data('module-id');
                    const $card = $(this);
                    
                    const $allCbsInModule = $card.find('input[name="permission[]"]');
                    const checkedInModule = $allCbsInModule.filter(':checked').length;

                    $card.find('.selected-count').text(checkedInModule);

                    const $rootCb = $('#permission-' + moduleId);
                    const $childrenCbs = $allCbsInModule.not($rootCb);

                    if ($childrenCbs.length > 0) {
                        if (checkedInModule > 0) {
                            $rootCb.prop('checked', true);
                        } else {
                            $rootCb.prop('checked', false);
                        }
                    }
                });
            }

            // Initial counter update
            updateCounters();

            // 1. Root Module Checkbox change -> check/uncheck all in module
            $(document).on('change', '.parent-permission-checkbox', function () {
                const isChecked = $(this).is(':checked');
                const $card = $(this).closest('.permission-module-card');
                $card.find('input[name="permission[]"]').prop('checked', isChecked);
                updateCounters();
                triggerRealtimeSync();
            });

            // 2. Menu Checkbox change -> check/uncheck all sub-actions in menu & ensure module checked
            $(document).on('change', '.menu-permission-checkbox', function () {
                const menuId = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`input[data-parent="${menuId}"]`).prop('checked', isChecked);

                if (isChecked) {
                    const parentModuleId = $(this).data('parent');
                    $('#permission-' + parentModuleId).prop('checked', true);
                }

                updateCounters();
                triggerRealtimeSync();
            });

            // 3. Action Checkbox change -> ensure menu and module checked
            $(document).on('change', '.action-permission-checkbox', function () {
                const isChecked = $(this).is(':checked');
                if (isChecked) {
                    const parentMenuId = $(this).data('parent');
                    const rootModuleId = $(this).data('root');

                    $('#permission-' + parentMenuId).prop('checked', true);
                    $('#permission-' + rootModuleId).prop('checked', true);
                }
                updateCounters();
                triggerRealtimeSync();
            });

            // Module Toggle button handler
            $(document).on('click', '.btn-toggle-module', function () {
                const parentId = $(this).data('parent');
                const $card = $(this).closest('.permission-module-card');
                const $allCbs = $card.find('input[name="permission[]"]');
                const allChecked = $allCbs.filter(':checked').length === $allCbs.length;

                $allCbs.prop('checked', !allChecked);
                updateCounters();
                triggerRealtimeSync();
            });

            // Select All Master Button
            $('#btn_select_all').on('click', function () {
                $('input[name="permission[]"]').prop('checked', true);
                updateCounters();
                triggerRealtimeSync();
            });

            // Deselect All Master Button
            $('#btn_deselect_all').on('click', function () {
                $('input[name="permission[]"]').prop('checked', false);
                updateCounters();
                triggerRealtimeSync();
            });

            // Live Search Filter
            $('#permission_search_input').on('keyup input', function () {
                const query = $(this).val().toLowerCase().trim();
                if (query.length > 0) {
                    $('#btn_clear_search').show();

                    $('.permission-module-card').each(function () {
                        let moduleMatch = false;
                        const moduleTitle = $(this).find('.module-title-label').text().toLowerCase();

                        if (moduleTitle.includes(query)) {
                            moduleMatch = true;
                        }

                        $(this).find('.permission-menu-box').each(function () {
                            let menuMatch = false;
                            const menuText = $(this).find('.permission-item-label').first().text().toLowerCase();

                            if (menuText.includes(query)) {
                                menuMatch = true;
                            }

                            $(this).find('.action-col').each(function () {
                                const actionText = $(this).text().toLowerCase();
                                if (actionText.includes(query)) {
                                    $(this).show();
                                    menuMatch = true;
                                } else {
                                    $(this).hide();
                                }
                            });

                            if (menuMatch || moduleTitle.includes(query)) {
                                $(this).closest('.menu-block-col').show();
                                if (moduleTitle.includes(query)) {
                                    $(this).find('.action-col').show();
                                }
                                moduleMatch = true;
                            } else {
                                $(this).closest('.menu-block-col').hide();
                            }
                        });

                        if (moduleMatch) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    $('#btn_clear_search').hide();
                    $('.permission-module-card, .menu-block-col, .action-col').show();
                }
            });

            $('#btn_clear_search').on('click', function () {
                $('#permission_search_input').val('').trigger('keyup');
            });
        });
    </script>
@endpush
