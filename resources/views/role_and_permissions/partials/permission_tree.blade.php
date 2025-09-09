@foreach ($permissions as $permission)
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading{{ $permission->id }}">
            <button style="width: 100%;text-align-last: left;border-radius: 10px;background: white;border: none;box-shadow: 0px 0px 3px 1px #b1b1b1;padding: 8px;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $permission->id }}" aria-expanded="false" aria-controls="collapse{{ $permission->id }}">
                <input type="checkbox" name="permission[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" class="me-2 parent-permission-checkbox" {{ in_array($permission->id, $permission_have) ? 'checked' : '' }}>
                {{ $permission->name }}
            </button>
        </h2>
        <div id="collapse{{ $permission->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $permission->id }}" data-bs-parent="#permissionsAccordion">
            <div class="accordion-body">
                @if ($permission->children->count() > 0)
                    <ul class="list-group">
                        @foreach ($permission->children as $childPermission)
                            <li class="list-group-item">
                                <input type="checkbox" name="permission[]" value="{{ $childPermission->id }}" id="permission-{{ $childPermission->id }}" data-parent="{{ $permission->id }}" class="me-2 child-permission-checkbox" {{ in_array($childPermission->id, $permission_have) ? 'checked' : '' }}>
                                {{ $childPermission->name }}
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