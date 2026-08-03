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
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('loans.show', [$loan->id]),
                        'editRoute' => route('loans.edit', [$loan->id]),
                        'deleteRoute' => route('loans.destroy', [$loan->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
