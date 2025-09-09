<!-- Name Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Interest Rate Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('interest_rate', 'Interest Rate:') !!}
        {!! Form::number('interest_rate', null, ['class' => 'form-control', 'step' => '0.01'])
        !!}
    </div>
</div>

<!-- Max Installments Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('max_installments', 'Max Installments:') !!}
        {!! Form::number('max_installments', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Loan Ceilings Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('loan_ceilings', 'Loan Ceilings:') !!}
        <div id="loan-ceilings-container">
            @if(isset($loanType) && $loanType->loan_ceilings)
                @foreach($loanType->loan_ceilings as $ceiling)
                    <div class="input-group mb-2">
                        <input type="text" name="loan_ceilings_grade[]" class="form-control" placeholder="Grade (e.g., Grade 1)" value="{{ $ceiling['grade'] ?? '' }}">
                        <input type="number" name="loan_ceilings_amount[]" class="form-control" placeholder="Amount (e.g., 8500000)" value="{{ $ceiling['amount'] ?? '' }}" step="0.01">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-loan-ceiling">-</button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="input-group mb-2">
                    <input type="text" name="loan_ceilings_grade[]" class="form-control" placeholder="Grade (e.g., Grade 1)">
                    <input type="number" name="loan_ceilings_amount[]" class="form-control" placeholder="Amount (e.g., 8500000)" step="0.01">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-loan-ceiling">-</button>
                    </div>
                </div>
            @endif
        </div>
        <button type="button" class="btn btn-success" id="add-loan-ceiling">+</button>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#add-loan-ceiling').click(function() {
            $('#loan-ceilings-container').append(
                '<div class="input-group mb-2">' +
                    '<input type="text" name="loan_ceilings_grade[]" class="form-control" placeholder="Grade (e.g., Grade 1)">' +
                    '<input type="number" name="loan_ceilings_amount[]" class="form-control" placeholder="Amount (e.g., 8500000)" step="0.01">' +
                    '<div class="input-group-append">' +
                        '<button type="button" class="btn btn-danger remove-loan-ceiling">-</button>' +
                    '</div>' +
                '</div>'
            );
        });

        $(document).on('click', '.remove-loan-ceiling', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
@endpush

<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control'])
        !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ route('loanTypes.index') }}" class="btn btn-default">Cancel</a>
</div>
