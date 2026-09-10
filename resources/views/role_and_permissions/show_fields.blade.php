<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center shadow-sm">
            <span class="text-muted small d-block mb-1">Role Name</span>
            <h5 class="fw-bold text-dark mb-0">{{ $roleAndPermission->name }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center shadow-sm">
            <span class="text-muted small d-block mb-1">System Key</span>
            <code class="fs-6 text-primary">{{ $roleAndPermission->key }}</code>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center shadow-sm">
            <span class="text-muted small d-block mb-1">Branch Scope</span>
            <span class="badge {{ $roleAndPermission->branch ? 'bg-info text-dark' : 'bg-secondary' }} fs-6">
                {{ $roleAndPermission->branch ? $roleAndPermission->branch->branch_name : 'All Branches' }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center shadow-sm">
            <span class="text-muted small d-block mb-1">Assigned Permissions</span>
            <span class="badge bg-success fs-6">
                {{ count($assignedPermissionIds) }} / {{ $totalPermissions }}
            </span>
        </div>
    </div>
</div>

<hr class="my-4">

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4" id="roleDetailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="matrix-tab" data-bs-toggle="tab" data-bs-target="#matrix-pane" type="button" role="tab">
            <i class="im im-icon-Table me-2 text-primary"></i> Access Control Matrix
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button" role="tab">
            <i class="im im-icon-Folder me-2 text-success"></i> Module Summary View
        </button>
    </li>
</ul>

<div class="tab-content" id="roleDetailTabsContent">
    <!-- Tab 1: Access Control Matrix -->
    <div class="tab-pane fade show active" id="matrix-pane" role="tabpanel">
        <div class="table-responsive bg-white rounded shadow-sm border">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 20%;">Module</th>
                        <th style="width: 25%;">Menu / Resource</th>
                        <th class="text-center" style="width: 9%;">View</th>
                        <th class="text-center" style="width: 9%;">Add</th>
                        <th class="text-center" style="width: 9%;">Edit</th>
                        <th class="text-center" style="width: 9%;">Delete</th>
                        <th class="text-center" style="width: 9%;">Export</th>
                        <th class="text-center" style="width: 10%;">Other / Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $hasAnyGranted = false; @endphp
                    @foreach($permissionMatrix as $row)
                        @php
                            $menuPerm = $row['menu_permission'];
                            $actions = $row['actions'];
                            
                            $hasView = $actions['view'] && in_array($actions['view']->id, $assignedPermissionIds);
                            $hasCreate = $actions['create'] && in_array($actions['create']->id, $assignedPermissionIds);
                            $hasEdit = $actions['edit'] && in_array($actions['edit']->id, $assignedPermissionIds);
                            $hasDelete = $actions['delete'] && in_array($actions['delete']->id, $assignedPermissionIds);
                            $hasExport = $actions['export'] && in_array($actions['export']->id, $assignedPermissionIds);
                            
                            $otherGranted = [];
                            foreach($actions['other'] as $oPerm) {
                                if (in_array($oPerm->id, $assignedPermissionIds)) {
                                    $otherGranted[] = $oPerm->name;
                                }
                            }

                            // Check menu permission itself
                            $hasMenuAccess = in_array($menuPerm->id, $assignedPermissionIds) || $hasView || $hasCreate || $hasEdit || $hasDelete || $hasExport || count($otherGranted) > 0;
                            if ($hasMenuAccess) { $hasAnyGranted = true; }
                        @endphp
                        <tr>
                            <td class="fw-bold text-dark">
                                <i class="im im-icon-Folder me-1 text-primary"></i> {{ $row['module'] }}
                            </td>
                            <td class="fw-semibold">
                                {{ $row['menu'] }}
                                @if($hasMenuAccess)
                                    <span class="badge bg-success-subtle text-success ms-1"><i class="im im-icon-CheckMark"></i> Accessible</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($actions['view'])
                                    @if($hasView || in_array($menuPerm->id, $assignedPermissionIds))
                                        <span class="text-success fw-bold fs-5">✓</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($actions['create'])
                                    @if($hasCreate)
                                        <span class="text-success fw-bold fs-5">✓</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($actions['edit'])
                                    @if($hasEdit)
                                        <span class="text-success fw-bold fs-5">✓</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($actions['delete'])
                                    @if($hasDelete)
                                        <span class="text-danger fw-bold fs-5">✓</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($actions['export'])
                                    @if($hasExport)
                                        <span class="text-primary fw-bold fs-5">✓</span>
                                    @else
                                        <span class="text-muted opacity-25">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(count($otherGranted) > 0)
                                    @foreach($otherGranted as $og)
                                        <span class="badge bg-secondary mb-1" title="{{ $og }}">{{ $og }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 2: Module Summary View -->
    <div class="tab-pane fade" id="summary-pane" role="tabpanel">
        @php
            $allPermissions = $roleAndPermission->permissions;
            $grouped = [];
            foreach ($allPermissions as $perm) {
                if ($perm->parent) {
                    $parentName = $perm->parent->name;
                } else {
                    $parentName = $perm->name;
                }
                $grouped[$parentName][] = $perm;
            }
        @endphp

        @if(count($grouped) > 0)
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($grouped as $moduleName => $perms)
                    <div class="col">
                        <div class="card h-100 border-light-subtle shadow-sm">
                            <div class="card-header bg-white py-2 px-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span><i class="im im-icon-Folder me-2 text-primary"></i> {{ $moduleName }}</span>
                                <span class="badge bg-primary rounded-pill">{{ count($perms) }}</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($perms as $p)
                                        <span class="badge bg-light text-dark border p-2">
                                            <i class="im im-icon-CheckMark me-1 text-success"></i> {{ $p->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted bg-light rounded">
                <i class="im im-icon-Information display-6 d-block mb-2"></i>
                No permissions assigned to this role yet.
            </div>
        @endif
    </div>
</div>
