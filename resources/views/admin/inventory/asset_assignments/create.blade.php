@extends('layouts.default')

@section('title')
Assign Asset @parent
@stop

@section('content')
<style>
    .draggable-list {
        min-height: 200px;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
    }
    .draggable-item {
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        cursor: grab;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }
    .draggable-item:active {
        cursor: grabbing;
    }
    .draggable-item.assigned {
        background: #eef2ff;
        border-color: #c7d2fe;
    }
    .item-details h6 {
        margin: 0 0 5px 0;
        font-weight: 600;
        color: #111827;
    }
    .item-details p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }
    .btn-remove {
        background: #ef4444;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-remove:hover {
        background: #dc2626;
    }
    .card-title-custom {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .search-input-wrapper {
        position: relative;
    }
    .search-input-wrapper i {
        position: absolute;
        left: 10px;
        top: 10px;
        color: #6b7280;
    }
    .search-input-wrapper input {
        padding-left: 30px;
    }
</style>

<div class="content px-3 mt-4">
    <div class="row">
        <!-- Left Column: Employee Using Device -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title-custom mb-4">
                        <i class="fa fa-user"></i> Employee Using Device
                    </h5>
                    
                    <div class="form-group">
                        <select id="user_id" class="form-control select2" style="width: 100%;">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="assigned-list" class="draggable-list mt-3">
                        <!-- Assigned assets will be rendered here -->
                        <div class="text-center text-muted py-4" id="assigned-empty-state">
                            Select an employee to view or assign devices.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stored Device -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title-custom mb-4">
                        <i class="fa fa-box"></i> Stored Device
                    </h5>

                    <div class="form-group">
                        <select id="category_id" class="form-control select2" style="width: 100%;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group search-input-wrapper">
                        <i class="fa fa-search"></i>
                        <input type="text" id="search_asset" class="form-control" placeholder="Search by name or number...">
                    </div>

                    <div id="available-list" class="draggable-list mt-3">
                        <!-- Available assets will be rendered here -->
                        <div class="text-center text-muted py-4" id="available-empty-state" style="display: none;">
                            No devices found.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<!-- SortableJS and Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    const userSelect = $('#user_id');
    const categorySelect = $('#category_id');
    const searchInput = $('#search_asset');
    const assignedList = document.getElementById('assigned-list');
    const availableList = document.getElementById('available-list');

    // Initialize Sortable for Available List (can be dragged to Assigned List)
    new Sortable(availableList, {
        group: {
            name: 'shared',
            pull: 'clone',
            put: false // Do not allow items to be put back via drag and drop
        },
        animation: 150,
        sort: false,
        onEnd: function (evt) {
            // Remove the clone if dropped outside
            if (evt.to === evt.from) {
                // do nothing
            }
        }
    });

    // Initialize Sortable for Assigned List (accepts from Available List)
    new Sortable(assignedList, {
        group: {
            name: 'shared',
            put: true
        },
        animation: 150,
        onAdd: function (evt) {
            const itemEl = evt.item;
            const assetId = itemEl.getAttribute('data-id');
            const userId = userSelect.val();

            if (!userId) {
                Swal.fire('Error', 'Please select an employee first.', 'error');
                itemEl.remove();
                return;
            }

            // Perform AJAX assignment
            $.ajax({
                url: '{{ route("admin.inventory.asset-assignments.ajax.assign") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    asset_id: assetId,
                    user_id: userId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload lists to show updated state
                        fetchEmployeeAssets();
                        fetchAvailableAssets();
                        Swal.fire({
                            icon: 'success',
                            title: 'Assigned',
                            text: 'Device successfully assigned.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        itemEl.remove();
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Failed to assign device.', 'error');
                    itemEl.remove();
                }
            });
        }
    });

    // Fetch Available Assets
    function fetchAvailableAssets() {
        const categoryId = categorySelect.val();
        const search = searchInput.val();

        $.ajax({
            url: '{{ route("admin.inventory.asset-assignments.ajax.available") }}',
            method: 'GET',
            data: { category_id: categoryId, search: search },
            success: function(response) {
                $('#available-list .draggable-item').remove();
                if (response.assets.length === 0) {
                    $('#available-empty-state').show();
                } else {
                    $('#available-empty-state').hide();
                    response.assets.forEach(asset => {
                        const html = `
                            <div class="draggable-item" data-id="${asset.id}">
                                <div class="item-details">
                                    <h6>${asset.asset_code}</h6>
                                    <p>${asset.category ? asset.category.name : asset.name}</p>
                                </div>
                            </div>
                        `;
                        $(availableList).append(html);
                    });
                }
            }
        });
    }

    // Fetch Employee Assets
    function fetchEmployeeAssets() {
        const userId = userSelect.val();
        
        $('#assigned-list .draggable-item').remove();
        
        if (!userId) {
            $('#assigned-empty-state').show();
            return;
        }

        $.ajax({
            url: '{{ route("admin.inventory.asset-assignments.ajax.employee-assets") }}',
            method: 'GET',
            data: { user_id: userId },
            success: function(response) {
                if (response.assignments.length === 0) {
                    $('#assigned-empty-state').show().text('No devices assigned to this employee.');
                } else {
                    $('#assigned-empty-state').hide();
                    response.assignments.forEach(assignment => {
                        const html = `
                            <div class="draggable-item assigned" data-assignment-id="${assignment.id}">
                                <div class="item-details">
                                    <h6>${assignment.asset.asset_code}</h6>
                                    <p>${assignment.asset.category ? assignment.asset.category.name : assignment.asset.name}</p>
                                </div>
                                <button class="btn-remove" onclick="removeAsset(${assignment.id})">
                                    <i class="fa fa-times"></i> Remove
                                </button>
                            </div>
                        `;
                        $(assignedList).append(html);
                    });
                }
            }
        });
    }

    // Initial fetch
    fetchAvailableAssets();

    // Event Listeners
    userSelect.on('change', fetchEmployeeAssets);
    categorySelect.on('change', fetchAvailableAssets);
    searchInput.on('keyup', debounce(fetchAvailableAssets, 300));

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Global remove function
    window.removeAsset = function(assignmentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to return this device?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.inventory.asset-assignments.ajax.remove") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        assignment_id: assignmentId
                    },
                    success: function(response) {
                        if (response.success) {
                            fetchEmployeeAssets();
                            fetchAvailableAssets();
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed',
                                text: 'Device has been returned.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to remove device.', 'error');
                    }
                });
            }
        });
    };
});
</script>
@endpush
