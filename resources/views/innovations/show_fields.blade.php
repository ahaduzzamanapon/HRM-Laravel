<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $innovation->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $innovation->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('title', 'Title:') !!}</th>
    <td>{{ $innovation->title }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('description', 'Description:') !!}</th>
    <td>{{ $innovation->description }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('innovation_type', 'Innovation Type:') !!}</th>
    <td>{{ $innovation->innovation_type }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('submission_date', 'Submission Date:') !!}</th>
    <td>{{ $innovation->submission_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('verifier_id', 'Verifier:') !!}</th>
    <td>{{ $innovation->verifier->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('verification_status', 'Verification Status:') !!}</th>
    <td>{{ $innovation->verification_status }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('verification_date', 'Verification Date:') !!}</th>
    <td>{{ $innovation->verification_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $innovation->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('document', 'Document:') !!}</th>
    <td>
        @if($innovation->document)
            <a href="{{ asset($innovation->document) }}" target="_blank">View Document</a>
        @else
            N/A
        @endif
    </td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $innovation->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $innovation->updated_at }}</td>
</tr>


