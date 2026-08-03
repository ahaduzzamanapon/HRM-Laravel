<!-- Basic Info Section -->
<div class="row g-3 mb-4">
    <!-- Name Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('name', 'Role Name:', ['class'=>'form-label fw-bold text-dark']) !!}
            {!! Form::text('name', null, ['class' => 'form-control form-control-lg', 'id' => 'role_name_input', 'placeholder' => 'e.g. HR Assistant', 'required']) !!}
            <small class="text-muted">Descriptive title for this role.</small>
        </div>
    </div>
    <!-- Key Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('key', 'Role System Key:', ['class'=>'form-label fw-bold text-dark']) !!}
            {!! Form::text('key', null, ['class' => 'form-control form-control-lg font-monospace', 'id' => 'role_key_input', 'placeholder' => 'e.g. hr_assistant', 'required']) !!}
            <small class="text-muted">Unique key used in code & permissions check.</small>
        </div>
    </div>
    <!-- Branch Scope Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('branch_id', 'Branch Scope:', ['class'=>'form-label fw-bold text-dark']) !!}
            @if(isSuperAdmin())
                {!! Form::select('branch_id', ['' => 'All Branches'] + (isset($branches) ? $branches->toArray() : []), null, ['class' => 'form-select form-select-lg']) !!}
                <small class="text-muted">Super Admin can assign this role to any branch or make it global.</small>
            @else
                @php
                    $isEditMode = isset($roleAndPermission) && !empty($roleAndPermission->id);
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
            <i class="im im-icon-Key me-2"></i> Permissions Assignment Matrix
        </h5>
        <p class="text-muted small mb-0">Select the permissions and sub-permissions assigned to this role.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge bg-primary fs-6 px-3 py-2" id="total_permissions_counter">
            Selected: <span id="selected_perm_count">0</span> Permissions
        </span>
        <button type="button" class="btn btn-outline-success btn-sm" id="btn_select_all">
            <i class="im im-icon-CheckMark me-1"></i> Select All
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm" id="btn_deselect_all">
            <i class="im im-icon-Close me-1"></i> Deselect All
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn_toggle_expand">
            <i class="im im-icon-Arrow-Down me-1"></i> Expand / Collapse All
        </button>
    </div>
</div>

<div class="col-md-12 mb-4">
    <div class="accordion" id="permissionsAccordion">
        @include('role_and_permissions.partials.permission_tree', ["permissions" => $permissions, "permission_have" => $permission_have])
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex justify-content-end gap-2 border-top pt-3">
    <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-secondary px-4">
        <i class="im im-icon-Close me-1"></i> Cancel
    </a>
    <button type="submit" class="btn btn-primary px-4 shadow-sm">
        <i class="im im-icon-Disk me-1"></i> Save Role & Permissions
    </button>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            let isKeyManuallyEdited = false;

            $('#role_key_input').on('keyup input', function () {
                isKeyManuallyEdited = $(this).val().trim().length > 0;
            });

            $('#role_name_input').on('keyup input', function () {
                if (!isKeyManuallyEdited) {
                    const slug = $(this).val()
                        .toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '_')
                        .replace(/^-+|-+$/g, '');
                    $('#role_key_input').val(slug);
                }
            });

            function updateCounters() {
                const totalCheckboxes = $('input[name="permission[]"]:checked').length;
                $('#selected_perm_count').text(totalCheckboxes);

                $('.permission-module-card').each(function () {
                    const moduleId = $(this).data('module-id');
                    const totalChildren = $(this).find('.child-permission-checkbox').length;
                    const checkedChildren = $(this).find('.child-permission-checkbox:checked').length;
                    
                    $(this).find('.selected-count').text(checkedChildren);
                    
                    const $parentCb = $('#permission-' + moduleId);
                    if (totalChildren > 0) {
                        if (checkedChildren > 0) {
                            $parentCb.prop('checked', true);
                        } else {
                            $parentCb.prop('checked', false);
                        }
                    }
                });
            }

            // Initial counter update
            updateCounters();

            // Parent checkbox change handler
            $(document).on('change', '.parent-permission-checkbox', function () {
                const parentId = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`.child-permission-checkbox[data-parent="${parentId}"]`).prop('checked', isChecked);
                updateCounters();
            });

            // Child checkbox change handler
            $(document).on('change', '.child-permission-checkbox', function () {
                updateCounters();
            });

            // Module Toggle button handler
            $(document).on('click', '.btn-toggle-module', function () {
                const parentId = $(this).data('parent');
                const $childCbs = $(`.child-permission-checkbox[data-parent="${parentId}"]`);
                const $parentCb = $('#permission-' + parentId);
                const allChecked = $childCbs.filter(':checked').length === $childCbs.length;

                $childCbs.prop('checked', !allChecked);
                $parentCb.prop('checked', !allChecked);
                updateCounters();
            });

            // Select All Master Button
            $('#btn_select_all').on('click', function () {
                $('input[name="permission[]"]').prop('checked', true);
                updateCounters();
            });

            // Deselect All Master Button
            $('#btn_deselect_all').on('click', function () {
                $('input[name="permission[]"]').prop('checked', false);
                updateCounters();
            });

            // Toggle Expand/Collapse All
            let isExpanded = true;
            $('#btn_toggle_expand').on('click', function () {
                if (isExpanded) {
                    $('.accordion-collapse.collapse').collapse('hide');
                } else {
                    $('.accordion-collapse.collapse').collapse('show');
                }
                isExpanded = !isExpanded;
            });
        });
    </script>
@endpush
