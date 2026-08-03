@foreach ($permissions as $permission)
    @php
        $childCount = $permission->children->count();
        $selectedChildren = $permission->children->filter(function($child) use ($permission_have) {
            return in_array($child->id, $permission_have);
        })->count();
    @endphp
    <div class="card mb-3 border border-light-subtle shadow-sm rounded-3 overflow-hidden permission-module-card" data-module-id="{{ $permission->id }}">
        <div class="card-header bg-white py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
            <div class="d-flex align-items-center flex-grow-1 cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse{{ $permission->id }}" aria-expanded="true">
                <div class="form-check me-2" onclick="event.stopPropagation();">
                    <input type="checkbox" 
                           name="permission[]" 
                           value="{{ $permission->id }}" 
                           id="permission-{{ $permission->id }}" 
                           class="form-check-input parent-permission-checkbox cursor-pointer" 
                           {{ in_array($permission->id, $permission_have) ? 'checked' : '' }}>
                </div>
                <label class="form-check-label fw-bold text-dark cursor-pointer mb-0 fs-6 ms-2" for="permission-{{ $permission->id }}">
                    <i class="im im-icon-Folder me-2 text-primary"></i> {{ $permission->name }}
                </label>
                <span class="badge bg-light text-dark border ms-2 py-1 px-2 module-selection-badge" id="badge-module-{{ $permission->id }}">
                    <span class="selected-count">{{ $selectedChildren }}</span> / {{ $childCount }} selected
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                @if ($childCount > 0)
                <button type="button" 
                        class="btn btn-sm btn-outline-secondary py-0 px-2 btn-toggle-module" 
                        data-parent="{{ $permission->id }}" 
                        onclick="event.stopPropagation();">
                    Toggle Module
                </button>
                @endif
                <button type="button" 
                        class="btn btn-sm btn-link text-decoration-none text-secondary p-0" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse{{ $permission->id }}" 
                        aria-expanded="true">
                    <i class="im im-icon-Arrow-Down"></i>
                </button>
            </div>
        </div>

        <div id="collapse{{ $permission->id }}" class="collapse show" data-bs-parent="#permissionsAccordion">
            <div class="card-body bg-light-subtle p-3">
                @if ($childCount > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                        @foreach ($permission->children as $childPermission)
                            <div class="col">
                                <div class="p-2 bg-white rounded border d-flex align-items-center h-100 shadow-sm-hover permission-child-item">
                                    <div class="form-check mb-0 d-flex align-items-center">
                                        <input type="checkbox" 
                                               name="permission[]" 
                                               value="{{ $childPermission->id }}" 
                                               id="permission-{{ $childPermission->id }}" 
                                               data-parent="{{ $permission->id }}" 
                                               class="form-check-input child-permission-checkbox cursor-pointer mt-0 me-3" 
                                               {{ in_array($childPermission->id, $permission_have) ? 'checked' : '' }}>
                                        <label class="form-check-label text-secondary fw-semibold cursor-pointer mb-0 ps-2" for="permission-{{ $childPermission->id }}">
                                            {{ $childPermission->name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0"><i class="im im-icon-Information me-1"></i> Root module permission only (no sub-permissions).</p>
                @endif
            </div>
        </div>
    </div>
@endforeach