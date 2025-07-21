<!-- Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name', 'Name:',['class'=>'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>
<!-- Key Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('key', 'Key:',['class'=>'control-label']) !!}
        {!! Form::text('key', null, ['class' => 'form-control']) !!}
    </div>
</div>
@php
    $permissions = DB::table('permissions')->whereNull('cat_id')->get();

    $per = [];

    foreach ($permissions as $value) {
        $ker_per = $value->key;
        $per_data = DB::table('permissions')->where('cat_id', $ker_per)->get();
        $value->subdata = $per_data;
        $per[] = $value;
    }
@endphp

<div class="col-md-12">
    <div class="accordion" id="permissionsAccordion" style="display: flex;gap: 10px;flex-direction: column;margin-bottom: 16px;">
        @foreach ($per as $permission)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $permission->id }}">
                    <button style="width: 100%;text-align-last: left;border-radius: 10px;background: white;border: none;box-shadow: 0px 0px 3px 1px #b1b1b1;padding: 8px;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $permission->id }}" aria-expanded="false" aria-controls="collapse{{ $permission->id }}">
                        <input type="checkbox" name="permission[]" {{(isset($permission_have) && in_array($permission->id,$permission_have)) ? 'checked' : '', }} value="{{ $permission->id }}" onchange="editper(this,$permission->id)" id="perm-{{ $permission->id }}" class="me-2">
                        {{ $permission->name }}
                    </button>
                </h2>
                <div id="collapse{{ $permission->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $permission->id }}" data-bs-parent="#permissionsAccordion" >
                    <div class="accordion-body">
                        @if ($permission->subdata->count() > 0)
                            <ul class="list-group">
                                @foreach ($permission->subdata as $subpermission)
                                    <li class="list-group-item">
                                        <input type="checkbox" name="permission[]" {{(isset($permission_have) && in_array($subpermission->id,$permission_have)) ? 'checked' : '', }} value="{{ $subpermission->id }}" onchange="editper(this,$subpermission->id)" id="subperm-{{ $subpermission->id }}" data-parent="{{ $permission->id }}" class="me-2 per_data cat_{{ $permission->id }}">
                                        {{ $subpermission->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No sub-permissions available.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-danger">Cancel</a>
</div>

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.per_data').change(function () {
                const $parentCheckbox = $(`#perm-${$(this).data('parent')}`);
                const parentChecked = $parentCheckbox.is(':checked');
                if (this.checked) {
                    $parentCheckbox.prop('checked', true);
                } else {
                    const hasCheckedChild = $(`.cat_${$(this).data('parent')}`).get().some(el => el.checked);
                    if (!hasCheckedChild) {
                        $parentCheckbox.prop('checked', false);
                    }
                }
            });
        });
    </script>
@endsection
