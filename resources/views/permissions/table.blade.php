<div class="table-responsive">
    <table class="table" id="permissions-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Key</th>
                <th>Parent Permission</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($permissions as $key => $permission)
            <tr>
                <td>{{ $permission->id }}</td>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->key }}</td>
                <td>{{ $permission->parent->name ?? 'Root' }}</td>
                <td>{{ $permission->created_at }}</td>
                <td>{{ $permission->updated_at }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('permissions.show', [$permission->id]),
                        'editRoute' => route('permissions.edit', [$permission->id]),
                        'deleteRoute' => route('permissions.destroy', [$permission->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
