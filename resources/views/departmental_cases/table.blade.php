<div class="table-responsive">
    <table class="table" id="departmental-cases-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Allegation Type</th>
                <th>Allegation Category</th>
                <th>Penalty</th>
                <th>Final Action Taken</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($departmentalCases as $departmentalCase)
            <tr>
                <td>{{ $departmentalCase->employee->name }}</td>
                <td>{{ $departmentalCase->allegation_type }}</td>
                <td>{{ $departmentalCase->allegation_category }}</td>
                <td>{{ $departmentalCase->penalty->name ?? 'N/A' }}</td>
                <td>{{ $departmentalCase->final_action_taken }}</td>
                <td>
                    {!! Form::open(['route' => ['departmentalCases.destroy', $departmentalCase->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('departmentalCases.show', [$departmentalCase->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('departmentalCases.edit', [$departmentalCase->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
