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

<div class="col-md-12">
    <div class="accordion" id="permissionsAccordion" style="display: flex;gap: 10px;flex-direction: column;margin-bottom: 16px;">
        @include('role_and_permissions.partials.permission_tree', ["permissions" => $permissions, "permission_have" => $permission_have])
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('roleAndPermissions.index') }}" class="btn btn-danger">Cancel</a>
</div>

@section('scripts')
    @parent
    <script>
        $(document).ready(function () {
            // Function to handle parent checkbox change
            $(".parent-permission-checkbox").change(function () {
                const parentId = $(this).val();
                const isChecked = $(this).is(':checked');
                $(".child-permission-checkbox[data-parent=\"${parentId}\"]").prop('checked', isChecked);
            });

            // Function to handle child checkbox change
            $(".child-permission-checkbox").change(function () {
                const parentId = $(this).data('parent');
                const $parentCheckbox = $("#permission-" + parentId);
                const hasCheckedChild = $(".child-permission-checkbox[data-parent=\"${parentId}\"]:checked").length > 0;
                $parentCheckbox.prop('checked', hasCheckedChild);

                // Explicitly trigger change event on parent to ensure its state is re-evaluated
                $parentCheckbox.trigger('change');
            });

            // Initial state setup
            $(document).ready(function() {
                $(".parent-permission-checkbox").each(function() {
                    const parentId = $(this).val();
                    const hasCheckedChild = $(".child-permission-checkbox[data-parent=\"${parentId}\"]:checked").length > 0;
                    $(this).prop('checked', hasCheckedChild);
                    // Trigger change event to ensure any dependent logic runs
                    $(this).trigger('change');
                });
            });
    </script>
@endsection
