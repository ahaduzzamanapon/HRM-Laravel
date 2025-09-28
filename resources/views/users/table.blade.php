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
               
                <th>Action</th>
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
                    <div class='btn-group'>
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" data-bs-boundary="window" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('users.show', [$users->id]) }}" class='dropdown-item'>View</a></li>
                            <li><a href="{{ route('users.edit', [$users->id]) }}" class='dropdown-item'>Edit</a></li>
                            <li><a href="#" class='dropdown-item transfer-employee' onclick="openTransferModal('{{ $users->id }}', '{{ $users->name }} {{ $users->last_name }}', '{{ $users->branch->id ?? '' }}', '{{ $users->branch->branch_name ?? '' }}')">Transfer</a></li>
                            <li>
                                {!! Form::open(['route' => ['users.destroy', $users->id], 'method' => 'delete']) !!}
                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">Delete</button>
                                {!! Form::close() !!}
                            </li>
                        </ul>
                    </div>
                    @include('users.partials.transfer_modal', ['user' => $users])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
