<div class="table-responsive">
    <table class="table" id="roleAndPermissions-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        {{-- <th>Key</th> --}}
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($roleAndPermissions as $key => $roleAndPermission)
            <tr>
                <td>{{ $roleAndPermission->id }}</td>
            <td>{{ $roleAndPermission->name }}</td>
            {{-- <td>{{ $roleAndPermission->key }}</td> --}}
                <td>
                    {!! Form::open(['route' => ['roleAndPermissions.destroy', $roleAndPermission->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('roleAndPermissions.edit', [$roleAndPermission->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
