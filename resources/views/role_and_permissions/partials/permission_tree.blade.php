@foreach ($permissionTree as $module)
    @php
        $moduleChildren = $module->children ?? collect();
        $totalModulePerms = 1; // root module permission itself
        $selectedModulePerms = in_array($module->id, $permission_have) ? 1 : 0;

        foreach($moduleChildren as $menu) {
            $totalModulePerms++;
            if (in_array($menu->id, $permission_have)) { $selectedModulePerms++; }
            
            if ($menu->children) {
                foreach($menu->children as $action) {
                    $totalModulePerms++;
                    if (in_array($action->id, $permission_have)) { $selectedModulePerms++; }
                }
            }
        }
    @endphp
    <div class="permission-module-card mb-4 pb-3 border-bottom" data-module-id="{{ $module->id }}">
        <!-- Module Header Row -->
        <div class="d-flex align-items-center mb-2">
            <div class="form-check me-3 mb-0">
                <input type="checkbox" 
                       name="permission[]" 
                       value="{{ $module->id }}" 
                       id="permission-{{ $module->id }}" 
                       class="form-check-input parent-permission-checkbox cursor-pointer" 
                       {{ in_array($module->id, $permission_have) ? 'checked' : '' }}>
            </div>
            <label class="form-check-label fw-bold text-dark fs-5 cursor-pointer mb-0 me-2 module-title-label" for="permission-{{ $module->id }}">
                {{ $module->name }}
            </label>
            <span class="badge bg-light text-secondary border py-1 px-2 module-selection-badge" id="badge-module-{{ $module->id }}">
                <span class="selected-count">{{ $selectedModulePerms }}</span> / {{ $totalModulePerms }} selected
            </span>
        </div>

        <!-- Module Items (Flat Tree View) -->
        <div class="ps-3 pt-1">
            @if ($moduleChildren->count() > 0)
                <div class="row g-3">
                    @foreach ($moduleChildren as $menu)
                        @php
                            $hasSubActions = $menu->children && $menu->children->count() > 0;
                        @endphp
                        <div class="{{ $hasSubActions ? 'col-12' : 'col-md-6 col-lg-4' }} menu-block-col" data-parent-module="{{ $module->id }}">
                            <div class="permission-menu-box py-1">
                                <div class="d-flex align-items-center {{ $hasSubActions ? 'mb-2 pb-1 border-bottom border-light' : '' }}">
                                    <div class="form-check me-3 mb-0">
                                        <input type="checkbox" 
                                               name="permission[]" 
                                               value="{{ $menu->id }}" 
                                               id="permission-{{ $menu->id }}" 
                                               data-parent="{{ $module->id }}" 
                                               class="form-check-input menu-permission-checkbox cursor-pointer" 
                                               {{ in_array($menu->id, $permission_have) ? 'checked' : '' }}>
                                    </div>
                                    <label class="form-check-label text-dark fw-bold cursor-pointer mb-0 permission-item-label" for="permission-{{ $menu->id }}">
                                        {{ $menu->name }}
                                    </label>
                                    @if($hasSubActions)
                                        <span class="badge bg-light text-secondary border small ms-2">
                                            Actions: {{ $menu->children->count() }}
                                        </span>
                                    @endif
                                </div>

                                @if($hasSubActions)
                                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 ps-4 pt-1">
                                        @foreach ($menu->children as $action)
                                            <div class="col action-col">
                                                <div class="d-flex align-items-center py-1 permission-child-item">
                                                    <div class="form-check me-3 mb-0">
                                                        <input type="checkbox" 
                                                               name="permission[]" 
                                                               value="{{ $action->id }}" 
                                                               id="permission-{{ $action->id }}" 
                                                               data-parent="{{ $menu->id }}" 
                                                               data-root="{{ $module->id }}" 
                                                               class="form-check-input action-permission-checkbox cursor-pointer" 
                                                               {{ in_array($action->id, $permission_have) ? 'checked' : '' }}>
                                                    </div>
                                                    <label class="form-check-label text-secondary fw-semibold cursor-pointer mb-0 small permission-item-label" for="permission-{{ $action->id }}">
                                                        {{ $action->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-0 py-1"><i class="im im-icon-Information me-1"></i> Root module permission only (no sub-permissions).</p>
            @endif
        </div>
    </div>
@endforeach