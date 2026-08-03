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
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('loanRepayments.show', [$loanRepayment->id]),
                        'editRoute' => route('loanRepayments.edit', [$loanRepayment->id]),
                        'deleteRoute' => route('loanRepayments.destroy', [$loanRepayment->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
