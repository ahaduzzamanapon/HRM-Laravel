<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $loanType->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('name', 'Name:') !!}</th>
    <td>{{ $loanType->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('description', 'Description:') !!}</th>
    <td>{{ $loanType->description }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('interest_rate', 'Interest Rate:') !!}</th>
    <td>{{ $loanType->interest_rate }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('max_installments', 'Max Installments:') !!}</th>
    <td>{{ $loanType->max_installments }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('loan_ceilings', 'Loan Ceilings:') !!}</th>
    <td>
        @if($loanType->loan_ceilings)
            <ul>
                @foreach($loanType->loan_ceilings as $ceiling)
                    <li>Grade: {{ $ceiling['grade'] ?? 'N/A' }}, Amount: {{ $ceiling['amount'] ?? 'N/A' }}</li>
                @endforeach
            </ul>
        @else
            N/A
        @endif
    </td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $loanType->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $loanType->updated_at }}</td>
</tr>


