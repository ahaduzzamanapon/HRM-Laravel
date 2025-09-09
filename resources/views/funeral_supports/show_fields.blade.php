<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $funeralSupport->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $funeralSupport->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('amount', 'Amount:') !!}</th>
    <td>{{ $funeralSupport->amount }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('support_date', 'Support Date:') !!}</th>
    <td>{{ $funeralSupport->support_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $funeralSupport->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $funeralSupport->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $funeralSupport->updated_at }}</td>
</tr>


