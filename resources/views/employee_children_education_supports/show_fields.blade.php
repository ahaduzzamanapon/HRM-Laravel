<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('child_name', 'Child Name:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->child_name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('exam_name', 'Exam Name:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->exam_name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('gpa', 'GPA:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->gpa }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('financial_assistance', 'Financial Assistance:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->financial_assistance }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('support_date', 'Support Date:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->support_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $employeeChildrenEducationSupport->updated_at }}</td>
</tr>


