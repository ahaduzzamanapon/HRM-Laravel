<div class="row">
    <div class="col-md-6">
        <!-- Shift Name Field -->
        <div class="form-group">
            {!! Form::label('shift_name', 'Shift Name:') !!}
            {!! Form::text('shift_name', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Branch Id Field -->
        <div class="form-group">
            {!! Form::label('branch_id', 'Branch:') !!}
            {!! Form::select('branch_id', $branches, null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<hr>
<h5>Day-wise Shift Details</h5>

@php
    $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
@endphp

@foreach($daysOfWeek as $day)
<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">{{ $day }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_in_time', 'In Time:') !!}
                    {!! Form::time($day . '_in_time', isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->in_time : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_out_time', 'Out Time:') !!}
                    {!! Form::time($day . '_out_time', isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->out_time : null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_late_start_time', 'Late Start Time:') !!}
                    {!! Form::time($day . '_late_start_time', isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->late_start_time : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_lunch_start_time', 'Lunch Start Time:') !!}
                    {!! Form::time($day . '_lunch_start_time', isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->lunch_start_time : null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_lunch_end_time', 'Lunch End Time:') !!}
                    {!! Form::time($day . '_lunch_end_time', isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->lunch_end_time : null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label($day . '_is_weekend', 'Is Weekend:') !!}
                    {!! Form::checkbox($day . '_is_weekend', 1, isset($shift) && $shift->shiftDetails->where('day_of_week', $day)->first() ? $shift->shiftDetails->where('day_of_week', $day)->first()->is_weekend : null, ['class' => 'form-check-input weekend-checkbox', 'data-day' => $day]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
        
            $('.weekend-checkbox').change(function() {
                const day = $(this).data('day');
                const isChecked = $(this).is(':checked');

                $(`input[name="${day}_in_time"]`).prop('disabled', isChecked).val('');
                $(`input[name="${day}_out_time"]`).prop('disabled', isChecked).val('');
                $(`input[name="${day}_late_start_time"]`).prop('disabled', isChecked).val('');
                $(`input[name="${day}_lunch_start_time"]`).prop('disabled', isChecked).val('');
                $(`input[name="${day}_lunch_end_time"]`).prop('disabled', isChecked).val('');
            }).trigger('change'); // Trigger on load to set initial state
        });
    </script> --}}
@endpush
