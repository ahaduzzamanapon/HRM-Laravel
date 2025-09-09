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
                    {!! Form::open(['route' => ['loanTypes.destroy', $loanType->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('loanTypes.show', [$loanType->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('loanTypes.edit', [$loanType->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
