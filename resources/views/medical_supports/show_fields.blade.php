<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $medicalSupport->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $medicalSupport->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('amount', 'Amount:') !!}</th>
    <td>{{ $medicalSupport->amount }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('support_date', 'Support Date:') !!}</th>
    <td>{{ $medicalSupport->support_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $medicalSupport->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $medicalSupport->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $medicalSupport->updated_at }}</td>
</tr>


