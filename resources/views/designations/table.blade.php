<div class="table-responsive">
    <table class="table" id="designations-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        <th>Status</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($designations as $key => $designation)
            <tr>
                <td>{{ $designation->id }}</td>
            <td>{{ $designation->desi_name }}</td>
            <td>{{ $designation->desi_status }}</td>

                <td>
                    <div class='btn-group'>
                        <a href="{{ route('designations.show', [$designation->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('designations.edit', [$designation->id]) }}" class='btn btn-outline-primary btn-xs'><i
                            class="im im-icon-Pen"  data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                        @if(can('delete_option'))
                            {!! Form::open(['route' => ['designations.destroy', $designation->id], 'method' => 'delete']) !!}
                            {!! Form::button('<i class="im im-icon-Remove" data-toggle="tooltip" data-placement="top" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
