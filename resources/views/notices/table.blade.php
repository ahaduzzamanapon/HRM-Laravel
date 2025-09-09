<div class="table-responsive">
    <table class="table" id="notices-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Title</th>
        <th>Status</th>
        <th>Documents</th>
        <th>Description</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($notices as $key => $notice)
            <tr>
                <td>{{ $notice->id }}</td>
            <td>{{ $notice->title }}</td>
            <td>{{ $notice->status }}</td>
            <td>{{ $notice->documents }}</td>
            <td>{{ $notice->description }}</td>
            <td>{{ $notice->created_at }}</td>
            <td>{{ $notice->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['notices.destroy', $notice->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('notices.show', [$notice->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('notices.edit', [$notice->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
