<div class="table-responsive">
    <table class="table" id="medical-supports-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Amount</th>
                <th>Support Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($medicalSupports as $medicalSupport)
            <tr>
                <td>{{ $medicalSupport->employee->name }}</td>
                <td>{{ $medicalSupport->amount }}</td>
                <td>{{ $medicalSupport->support_date }}</td>
                <td>{{ $medicalSupport->remarks }}</td>
                <td>
                    {!! Form::open(['route' => ['medicalSupports.destroy', $medicalSupport->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('medicalSupports.show', [$medicalSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('medicalSupports.edit', [$medicalSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
