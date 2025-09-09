<div class="table-responsive">
    <table class="table" id="penalties-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($penalties as $penalty)
            <tr>
                <td>{{ $penalty->name }}</td>
                <td>{{ $penalty->type }}</td>
                <td>{{ $penalty->description }}</td>
                <td>
                    {!! Form::open(['route' => ['penalties.destroy', $penalty->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('penalties.show', [$penalty->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('penalties.edit', [$penalty->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
