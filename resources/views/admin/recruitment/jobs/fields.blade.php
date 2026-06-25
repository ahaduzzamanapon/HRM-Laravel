<!-- Title Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('title', 'Title:', ['class' => 'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control', 'required', 'onkeyup' => 'auto_capitalize(this)']) !!}
    </div>
</div>

<!-- Slug Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('slug', 'Slug:', ['class' => 'control-label']) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug', 'required']) !!}
    </div>
</div>

<!-- Location Field -->
<div class="col-md-4">
    <div class="form-group">
        {!! Form::label('location', 'Location:', ['class' => 'control-label']) !!}
        {!! Form::text('location', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Status Field -->
<div class="col-md-4">
    <div class="form-group">
        {!! Form::label('status', 'Status:', ['class' => 'control-label']) !!}
        {!! Form::select('status', ['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed'], null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>

<!-- Deadline Field -->
<div class="col-md-4">
    <div class="form-group">
        {!! Form::label('deadline', 'Deadline:', ['class' => 'control-label']) !!}
        {!! Form::date('deadline', isset($jobPost) && $jobPost->deadline ? $jobPost->deadline->format('Y-m-d') : null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('description', 'Description:', ['class' => 'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description_editor', 'rows' => 5, 'required']) !!}
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-right">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.jobs.index') }}" class="btn btn-danger">Cancel</a>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script type="text/javascript">
    function auto_capitalize(s) {
        var str = s.value;
        var text = str.replace(/\s+/g,'-').toLowerCase();
        $('#slug').val(text);
    }

    $(document).ready(function() {
        CKEDITOR.replace('description_editor', {
            versionCheck: false,
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token() ]) }}",
            filebrowserUploadMethod: 'form'
        });
    });
</script>
@endpush
