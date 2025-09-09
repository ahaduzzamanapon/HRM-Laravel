<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $loanRepayment->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('loan_id', 'Loan Id:') !!}</th>
    <td>{{ $loanRepayment->loan->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('amount', 'Amount:') !!}</th>
    <td>{{ $loanRepayment->amount }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('repayment_date', 'Repayment Date:') !!}</th>
    <td>{{ $loanRepayment->repayment_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('remarks', 'Remarks:') !!}</th>
    <td>{{ $loanRepayment->remarks }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $loanRepayment->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $loanRepayment->updated_at }}</td>
</tr>


