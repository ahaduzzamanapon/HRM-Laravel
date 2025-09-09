<div class="table-responsive">
    <table class="table" id="loan-repayments-table">
        <thead>
            <tr>
                <th>Loan ID</th>
                <th>Amount</th>
                <th>Repayment Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($loanRepayments as $loanRepayment)
            <tr>
                <td>{{ $loanRepayment->loan->id }}</td>
                <td>{{ $loanRepayment->amount }}</td>
                <td>{{ $loanRepayment->repayment_date }}</td>
                <td>{{ $loanRepayment->remarks }}</td>
                <td>
                    {!! Form::open(['route' => ['loanRepayments.destroy', $loanRepayment->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('loanRepayments.show', [$loanRepayment->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('loanRepayments.edit', [$loanRepayment->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
