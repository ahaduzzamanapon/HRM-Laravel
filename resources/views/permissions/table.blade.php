<div class="table-responsive">
    <table class="table" id="permissions-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        <th>Key</th>
        <th>Cat Id</th>
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
            <td>{{ $permission->cat_id }}</td>
            <td>{{ $permission->created_at }}</td>
            <td>{{ $permission->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['permissions.destroy', $permission->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('permissions.show', [$permission->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('permissions.edit', [$permission->id]) }}" class='btn btn-outline-primary btn-xs'><i
                                class="im im-icon-Pen"  data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                        {!! Form::button('<i class="im im-icon-Remove" data-toggle="tooltip" data-placement="top" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
