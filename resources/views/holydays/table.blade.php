<div class="table-responsive">
    <table class="table" id="holydays-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Branch</th>
        <th>Title</th>
        <th>Status</th>
        <th>Date</th>
        <th>Descreption</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($holydays as $key => $holyday)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $holyday->branch_id }}</td>
            <td>{{ $holyday->title }}</td>
            <td>{{ $holyday->status }}</td>
            <td>{{ $holyday->date }}</td>
            <td>{{ $holyday->descreption }}</td>
           
                <td>
                    {!! Form::open(['route' => ['holydays.destroy', $holyday->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('holydays.show', [$holyday->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('holydays.edit', [$holyday->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
