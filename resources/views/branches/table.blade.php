<div class="table-responsive">
    <table class="table" id="branches-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Branch Name</th>
        <th>Address</th>
        <th>Status</th>
        <th>Description</th>
     
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($branches as $key => $branch)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $branch->branch_name }}</td>
            <td>{{ $branch->Address }}</td>
            <td>{{ $branch->status }}</td>
            <td>{{ $branch->description }}</td>
                <td>
                    {!! Form::open(['route' => ['branches.destroy', $branch->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('branches.show', [$branch->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('branches.edit', [$branch->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
