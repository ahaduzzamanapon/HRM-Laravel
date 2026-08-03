<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center">
            <span class="text-muted small d-block mb-1">Role Name</span>
            <h5 class="fw-bold text-dark mb-0">{{ $roleAndPermission->name }}</h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center">
            <span class="text-muted small d-block mb-1">System Key</span>
            <code class="fs-6 text-primary">{{ $roleAndPermission->key }}</code>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center">
            <span class="text-muted small d-block mb-1">Branch Scope</span>
            <span class="badge {{ $roleAndPermission->branch ? 'bg-info text-dark' : 'bg-secondary' }} fs-6">
                {{ $roleAndPermission->branch ? $roleAndPermission->branch->branch_name : 'All Branches' }}
            </span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="p-3 bg-light rounded border text-center">
            <span class="text-muted small d-block mb-1">Total Permissions</span>
            <span class="badge bg-success fs-6">
                {{ $roleAndPermission->permissions->count() }} Active
            </span>
        </div>
    </div>
</div>

<hr class="my-4">

<h5 class="fw-bold text-primary mb-3">
    <i class="im im-icon-Key me-2"></i> Assigned Permissions Matrix
</h5>

@php
    // Group permissions by parent
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


