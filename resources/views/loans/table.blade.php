<div class="table-responsive">
    <table class="table" id="loans-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Loan Type</th>
                <th>Amount</th>
                <th>Interest Rate</th>
                <th>Installments</th>
                <th>Monthly Installment</th>
                <th>Disbursement Date</th>
                <th>Outstanding Balance</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->employee->name }}</td>
                <td>{{ $loan->loanType->name }}</td>
                <td>{{ $loan->amount }}</td>
                <td>{{ $loan->interest_rate }}</td>
                <td>{{ $loan->installments }}</td>
                <td>{{ $loan->monthly_installment }}</td>
                <td>{{ $loan->disbursement_date }}</td>
                <td>{{ $loan->outstanding_balance }}</td>
                <td>{{ $loan->status }}</td>
                <td>
                    {!! Form::open(['route' => ['loans.destroy', $loan->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('loans.show', [$loan->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('loans.edit', [$loan->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
