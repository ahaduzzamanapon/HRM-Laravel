@php
    $canEdit = can('edit_employee');
    $canDelete = can('delete_employee');
@endphp
<div class="table-responsive">
    <table class="table table-hover table-striped table_data" id="users-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Emp Id</th>
                <th>Name</th>
                <th>Group</th>
                <th>Designation</th>
                <th>Shift</th>
                <th>Branch</th>
                <th data-orderable="false">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $u)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $u->emp_id }}</td>
                <td>{{ $u->name }} {{ $u->last_name }}</td>
                <td>{{ $u->role ?? '—' }}</td>
                <td>{{ $u->designation ?? '—' }}</td>
                <td>{{ $u->shift ?? '—' }}</td>
                <td>
                    <span class="badge bg-light text-dark border">
                        {{ $u->branch_name ?? '—' }}
                    </span>
                </td>
                <td>
                    @php
                        $extraButtons = '';
                        if($canEdit) {
                            $fullName = htmlspecialchars($u->name . ' ' . $u->last_name, ENT_QUOTES);
                            $bName = htmlspecialchars($u->branch_name ?? '', ENT_QUOTES);
                            $extraButtons = '<button type="button" class="btn-action btn-action-transfer transfer-employee" onclick="openTransferModal(\'' . $u->id . '\', \'' . $fullName . '\', \'' . ($u->branch_id ?? '') . '\', \'' . $bName . '\')" title="Transfer" data-bs-toggle="tooltip"><i class="fa fa-exchange"></i></button>';
                        }
                    @endphp
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('users.show', [$u->id]),
                        'editRoute' => $canEdit ? route('users.edit', [$u->id]) : null,
                        'showEdit' => $canEdit,
                        'deleteRoute' => $canDelete ? route('users.destroy', [$u->id]) : null,
                        'showDelete' => $canDelete,
                        'extraButtons' => $extraButtons
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
