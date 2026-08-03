<div class="table-responsive">
    <table class="table" id="loan-types-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Interest Rate</th>
                <th>Max Installments</th>
                <th>Loan Ceilings</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($loanTypes as $loanType)
            <tr>
                <td>{{ $loanType->name }}</td>
                <td>{{ $loanType->description }}</td>
                <td>{{ $loanType->interest_rate }}</td>
                <td>{{ $loanType->max_installments }}</td>
                <td>
                    @if($loanType->loan_ceilings)
                        @foreach($loanType->loan_ceilings as $ceiling)
                            Grade: {{ $ceiling['grade'] ?? 'N/A' }}, Amount: {{ $ceiling['amount'] ?? 'N/A' }}<br>
                        @endforeach
                    @else
                        N/A
                    @endif
                </td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('loanTypes.show', [$loanType->id]),
                        'editRoute' => route('loanTypes.edit', [$loanType->id]),
                        'deleteRoute' => route('loanTypes.destroy', [$loanType->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
