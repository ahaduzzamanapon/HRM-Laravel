<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $departmentalCase->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $departmentalCase->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('allegation_type', 'Allegation Type:') !!}</th>
    <td>{{ $departmentalCase->allegation_type }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('allegation_category', 'Allegation Category:') !!}</th>
    <td>{{ $departmentalCase->allegation_category }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('disciplinary_issue_details', 'Disciplinary Issue Details:') !!}</th>
    <td>{{ $departmentalCase->disciplinary_issue_details }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('committee_comments', 'Committee Comments:') !!}</th>
    <td>{{ $departmentalCase->committee_comments }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('penalty_id', 'Penalty:') !!}</th>
    <td>{{ $departmentalCase->penalty->name ?? 'N/A' }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('final_action_taken', 'Final Action Taken:') !!}</th>
    <td>{{ $departmentalCase->final_action_taken }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $departmentalCase->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $departmentalCase->updated_at }}</td>
</tr>


