<div class="table-responsive">
    <table class="table" id="funeral-supports-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Amount</th>
                <th>Support Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($funeralSupports as $key => $funeralSupport)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $funeralSupport->employee->name }}</td>
                <td>{{ $funeralSupport->amount }}</td>
                <td>{{ $funeralSupport->support_date }}</td>
                <td>{{ $funeralSupport->remarks }}</td>
                <td>
                    {!! Form::open(['route' => ['funeralSupports.destroy', $funeralSupport->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('funeralSupports.show', [$funeralSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('funeralSupports.edit', [$funeralSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
