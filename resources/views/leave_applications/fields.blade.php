<div class="row">
    <div class="col-md-6">
        <!-- Leave Type Field -->
        <div class="form-group">
            {!! Form::label('leave_type_id', 'Leave Type:') !!}
            {!! Form::select('leave_type_id', $leaveTypes, null, ['class' => 'form-control', 'placeholder' => 'Select Leave Type']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Start Date Field -->
        <div class="form-group">
            {!! Form::label('start_date', 'Start Date:') !!}
            {!! Form::date('start_date', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- End Date Field -->
        <div class="form-group">
            {!! Form::label('end_date', 'End Date:') !!}
            {!! Form::date('end_date', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <!-- Is Half Day Field -->
        <div class="form-group">
            {!! Form::label('is_half_day', 'Half Day:') !!}
            {!! Form::checkbox('is_half_day', 1, null, ['class' => 'form-check-input']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Reason Field -->
        <div class="form-group">
            {!! Form::label('reason', 'Reason:') !!}
            {!! Form::textarea('reason', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->can('approve_leave'))
<div class="row">
    <div class="col-md-6">
        <!-- Status Field -->
        <div class="form-group">
            {!! Form::label('status', 'Status:') !!}
            {!! Form::select('status', ['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected'], null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
@endif