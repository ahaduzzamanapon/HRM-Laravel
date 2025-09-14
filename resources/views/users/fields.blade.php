<!-- Emp Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('emp_id', 'Emp Id:',['class'=>'control-label']) !!}
        {!! Form::text('emp_id', null , ['class' => 'form-control', 'readonly','required']) !!}
    </div>
</div>


<!-- Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name', 'First Name:',['class'=>'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Last Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('last_name', 'Last Name:',['class'=>'control-label']) !!}
        {!! Form::text('last_name', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Email Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('email', 'Email:',['class'=>'control-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Date Of Birth Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date_of_birth', 'Date Of Birth:',['class'=>'control-label']) !!}
        {!! Form::date('date_of_birth', null, ['class' => 'form-control','id'=>'date_of_birth','required']) !!}
    </div>
</div>


<!-- Date Of Join Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date_of_join', 'Date Of Join:',['class'=>'control-label']) !!}
        {!! Form::date('date_of_join', null, ['class' => 'form-control','id'=>'date_of_join','required']) !!}
    </div>
</div>



<!-- Gender Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('gender', 'Gender:',['class'=>'control-label']) !!}
        {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control','required']) !!}
    </div>
</div>
@php
    $designations = \App\Models\Designation::all()->pluck('desi_name','id')->prepend('Select Designation', '')->toArray();
@endphp
<!-- Gender Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('designation_id', 'Designation:',['class'=>'control-label']) !!}
        {!! Form::select('designation_id',$designations, null, ['class' => 'form-control','required']) !!}
    </div>
</div>

@php
    $branches = \App\Models\Branch::all()->pluck('branch_name','id')->prepend('Select Branch', '')->toArray();
@endphp
<!-- Branch Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('branch_id', 'Branch:',['class'=>'control-label']) !!}
        {!! Form::select('branch_id',$branches, null, ['class' => 'form-control','required']) !!}
    </div>
</div>

@php
    $departments = \App\Models\Department::all()->pluck('name','id')->prepend('Select Department', '')->toArray();
@endphp
<!-- Department Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('department_id', 'Department:',['class'=>'control-label']) !!}
        {!! Form::select('department_id',$departments, null, ['class' => 'form-control','required']) !!}
    </div>
</div>

@php
    $roles = \App\Models\RoleAndPermission::all()->pluck('name','id')->toArray();
@endphp
<!-- Group Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('group_id', 'Roll:',['class'=>'control-label']) !!}
        {!! Form::select('group_id',$roles, null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address:',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Phone Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('phone_number', 'Phone Number:',['class'=>'control-label']) !!}
        {!! Form::number('phone_number', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Blood Group Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('blood_group', 'Blood Group:',['class'=>'control-label']) !!}
        {!! Form::select('blood_group', [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-'
        ], null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Religion Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('religion', 'Religion:',['class'=>'control-label']) !!}
        {!! Form::select('religion', ['Islam' => 'Islam', 'Hindu' => 'Hindu'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Marital Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('marital_status', 'Marital Status:',['class'=>'control-label']) !!}
        {!! Form::select('marital_status', ['Single' => 'Single', 'Married' => 'Married'], null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Punch Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('punch_id', 'Punch Id:',['class'=>'control-label']) !!}
        {!! Form::text('punch_id', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Is PF Member Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('is_pf_member', 'Is PF Member:',['class'=>'control-label']) !!}
        {!! Form::checkbox('is_pf_member', 1, null) !!}
    </div>
</div>


<!-- Password Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('password', 'Password:',['class'=>'control-label']) !!}
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Image Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('image', 'Image:',['class'=>'control-label']) !!}
        {!! Form::file('image', ['onchange' => 'previewImage(event)','accept' => 'image/*']) !!}
        <img id="imagePreview" src="{{ isset($user) ? asset($user->image) : '' }}" alt="Image Preview" style="display:none; margin-top:10px; max-width:100%; height:auto;" />
    </div>
</div>
<div class="clearfix"></div>


