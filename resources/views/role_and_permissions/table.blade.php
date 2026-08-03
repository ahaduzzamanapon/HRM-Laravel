<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="roleAndPermissions-table">
        <thead class="table-light">
            <tr>
                <th style="width: 70px;" class="ps-3">ID</th>
                <th>Role Name</th>
                <th>Key</th>
                <th>Permissions Assigned</th>
                <th>Branch Scope</th>
                <th style="width: 160px;" class="text-end pe-3">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($roleAndPermissions as $roleAndPermission)
            <tr>
                <td class="ps-3 text-muted">#{{ $roleAndPermission->id }}</td>
                <td class="fw-bold text-dark">
                    <i class="im im-icon-Shield me-1 text-primary"></i>
                    {{ $roleAndPermission->name }}
                </td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('roleAndPermissions.show', [$roleAndPermission->id]),
                        'editRoute' => route('roleAndPermissions.edit', [$roleAndPermission->id]),
                        'deleteRoute' => route('roleAndPermissions.destroy', [$roleAndPermission->id]),
                    ])
                </td>
                <td>
                    @php
                        $permCount = $roleAndPermission->permissions ? $roleAndPermission->permissions->count() : 0;
                    @endphp
                    <span class="badge {{ $permCount > 0 ? 'bg-success' : 'bg-warning text-dark' }}">
                        <i class="im im-icon-CheckMark me-1"></i> {{ $permCount }} {{ Str::plural('Permission', $permCount) }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $roleAndPermission->branch ? 'bg-info text-dark' : 'bg-secondary' }}">
                        <i class="im im-icon-Building me-1"></i> {{ $roleAndPermission->branch ? $roleAndPermission->branch->branch_name : 'All Branches' }}
                    </span>
                </td>
                <td class="text-end pe-3">
                    {!! Form::open(['route' => ['roleAndPermissions.destroy', $roleAndPermission->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                    <div class='btn-group btn-group-sm' role="group">
                        <a href="{{ route('roleAndPermissions.show', [$roleAndPermission->id]) }}" class='btn btn-outline-info' data-bs-toggle="tooltip" title="View Details">
                            <i class="im im-icon-Eye"></i>
                        </a>
                        <a href="{{ route('roleAndPermissions.edit', [$roleAndPermission->id]) }}" class='btn btn-outline-primary' data-bs-toggle="tooltip" title="Edit Role">
                            <i class="im im-icon-Pen"></i>
                        </a>
                        {!! Form::button('<i class="im im-icon-Remove"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger', 'data-bs-toggle' => 'tooltip', 'title' => 'Delete Role', 'onclick' => "return confirm('Are you sure you want to delete this role?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="im im-icon-Information display-6 d-block mb-2"></i>
                    No roles found. Click <strong>Add New Role</strong> to create one.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
