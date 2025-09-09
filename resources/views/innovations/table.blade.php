<div class="table-responsive">
    <table class="table" id="innovations-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Title</th>
                <th>Innovation Type</th>
                <th>Submission Date</th>
                <th>Verifier</th>
                <th>Verification Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($innovations as $innovation)
            <tr>
                <td>{{ $innovation->employee->name }}</td>
                <td>{{ $innovation->title }}</td>
                <td>{{ $innovation->innovation_type }}</td>
                <td>{{ $innovation->submission_date }}</td>
                <td>{{ $innovation->verifier->name }}</td>
                <td>{{ $innovation->verification_status }}</td>
                <td>
                    {!! Form::open(['route' => ['innovations.destroy', $innovation->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('innovations.show', [$innovation->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('innovations.edit', [$innovation->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
