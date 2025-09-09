<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $loan->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('employee_id', 'Employee:') !!}</th>
    <td>{{ $loan->employee->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('loan_type_id', 'Loan Type:') !!}</th>
    <td>{{ $loan->loanType->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('amount', 'Amount:') !!}</th>
    <td>{{ $loan->amount }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('interest_rate', 'Interest Rate:') !!}</th>
    <td>{{ $loan->interest_rate }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('installments', 'Installments:') !!}</th>
    <td>{{ $loan->installments }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('monthly_installment', 'Monthly Installment:') !!}</th>
    <td>{{ $loan->monthly_installment }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('disbursement_date', 'Disbursement Date:') !!}</th>
    <td>{{ $loan->disbursement_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('next_payment_date', 'Next Payment Date:') !!}</th>
    <td>{{ $loan->next_payment_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('outstanding_balance', 'Outstanding Balance:') !!}</th>
    <td>{{ $loan->outstanding_balance }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('status', 'Status:') !!}</th>
    <td>{{ $loan->status }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $loan->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $loan->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $loan->updated_at }}</td>
</tr>


