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
               
                <th data-orderable="false">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $key => $users)
            <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $users->emp_id }}</td>
            <td>{{ $users->name }} {{ $users->last_name }}</td>
            <td>{{ $users->role }}</td>
            <td>{{ $users->designation }}</td>
            <td>{{ $users->shift }}</td>
         
                <td>
                    @php
                        $extraButtons = '';
                        if(can('edit_employee')) {
                            $extraButtons = '<button type="button" class="btn-action btn-action-transfer transfer-employee" onclick="openTransferModal(\'' . $users->id . '\', \'' . $users->name . ' ' . $users->last_name . '\', \'' . ($users->branch->id ?? '') . '\', \'' . ($users->branch->branch_name ?? '') . '\')" title="Transfer" data-bs-toggle="tooltip"><i class="fa fa-exchange"></i></button>';
                        }
                    @endphp
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('users.show', [$users->id]),
                        'editRoute' => can('edit_employee') ? route('users.edit', [$users->id]) : null,
                        'showEdit' => can('edit_employee'),
                        'deleteRoute' => can('delete_employee') ? route('users.destroy', [$users->id]) : null,
                        'showDelete' => can('delete_employee'),
                        'extraButtons' => $extraButtons
                    ])
                    @include('users.partials.transfer_modal', ['user' => $users])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
