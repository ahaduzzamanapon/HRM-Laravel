<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Bookmark me-2"></i>Training Details</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="trainingAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#trainingAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="training-form-title">Add / Edit Training Detail</h5>
                        <form id="training-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="training-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="training_name" class="fw-bold mb-1">Training Name <span class="text-danger">*</span></label>
                                        <input type="text" name="training_name" id="training_name" class="form-control" required placeholder="e.g. Advanced Laravel & Web Architecture">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="training_provider" class="fw-bold mb-1">Training Provider <span class="text-danger">*</span></label>
                                        <input type="text" name="training_provider" id="training_provider" class="form-control" required placeholder="e.g. BASIS Institute of Technology">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="training_type" class="fw-bold mb-1">Training Type</label>
                                        <select name="training_type" id="training_type" class="form-select form-control">
                                            <option value="Domestic">Domestic</option>
                                            <option value="Foreign">Foreign</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date" class="fw-bold mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date" class="fw-bold mb-1">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="document_training" class="fw-bold mb-1">Training Certificate / Document:</label>
                                <input type="file" name="document" id="document_training" class="form-control">
                                <small class="text-muted d-block mt-1" id="current-document-link-training"></small>
                            </div>

                            <div class="form-group mt-3">
                                <label for="description" class="fw-bold mb-1">Description / Outcome:</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Summary of skills acquired..."></textarea>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-training-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-training-detail-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Training
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Training Details in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Training Name</th>
                        <th>Provider</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Document</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="training-details-table-body">
                    @if(isset($users) && $users->trainingDetails->count() > 0)
                        @foreach($users->trainingDetails as $trainingDetail)
                            <tr data-id="{{ $trainingDetail->id }}">
                                <td class="fw-bold text-dark">{{ $trainingDetail->training_name }}</td>
                                <td>{{ $trainingDetail->training_provider }}</td>
                                <td><span class="badge bg-secondary">{{ $trainingDetail->training_type }}</span></td>
                                <td>{{ $trainingDetail->start_date ?? 'N/A' }}</td>
                                <td>{{ $trainingDetail->end_date ?? 'N/A' }}</td>
                                <td>
                                    @if($trainingDetail->document)
                                        <a href="{{ asset($trainingDetail->document) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-training-detail" data-id="{{ $trainingDetail->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-training-detail" data-id="{{ $trainingDetail->id }}" title="Edit Training">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-training-detail" data-id="{{ $trainingDetail->id }}" title="Delete Training">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No training details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Training Detail Modal -->
<div class="modal fade" id="viewTrainingDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Bookmark me-2"></i>Training Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Training Name:</th>
                            <td class="fw-bold text-primary" id="view_train_name">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Provider:</th>
                            <td id="view_train_provider">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Type:</th>
                            <td id="view_train_type">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Start Date:</th>
                            <td id="view_train_start">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">End Date:</th>
                            <td id="view_train_end">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Description:</th>
                            <td id="view_train_desc">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Certificate File:</th>
                            <td id="view_train_doc">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const userId = "{{ $users->id }}";
        const trainingDetailForm = $('#training-detail-form');

        function toggleTrainingCollapse(show) {
            if (show) {
                $('#collapseOne').collapse('show');
            } else {
                $('#collapseOne').collapse('hide');
            }
        }

        function loadTrainingDetails() {
            $.ajax({
                url: `/trainingDetails/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.trainingDetail && res.trainingDetail.length > 0) {
                        res.trainingDetail.forEach(t => {
                            let docHtml = t.document 
                                ? `<a href="/${t.document}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${t.id}">
                                <td class="fw-bold text-dark">${t.training_name || 'N/A'}</td>
                                <td>${t.training_provider || 'N/A'}</td>
                                <td><span class="badge bg-secondary">${t.training_type || 'N/A'}</span></td>
                                <td>${t.start_date || 'N/A'}</td>
                                <td>${t.end_date || 'N/A'}</td>
                                <td>${docHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-training-detail" data-id="${t.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-training-detail" data-id="${t.id}" title="Edit Training">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-training-detail" data-id="${t.id}" title="Delete Training">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="7" class="text-center py-4 text-muted">No training details found.</td></tr>';
                    }
                    $('#training-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading training table:', xhr.responseText);
                }
            });
        }

        loadTrainingDetails();

        $('#cancel-training-btn').click(function() {
            trainingDetailForm[0].reset();
            $('#training-detail-id').val('');
            $('#current-document-link-training').html('');
            $('#training-form-title').text('Add / Edit Training Detail');
            toggleTrainingCollapse(false);
        });

        trainingDetailForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const tId = $('#training-detail-id').val();
            const url = tId ? `/trainingDetails/${tId}` : '/trainingDetails';

            if (tId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadTrainingDetails();
                    if(response.error){
                        alert(response.message || 'Error saving training detail');
                        return;
                    }
                    alert(response.message || 'Training detail saved successfully');
                    trainingDetailForm[0].reset();
                    $('#training-detail-id').val('');
                    $('#current-document-link-training').html('');
                    $('#training-form-title').text('Add / Edit Training Detail');
                    toggleTrainingCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving training detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Training Detail
        $(document).on('click', '.view-training-detail', function() {
            const tId = $(this).data('id');
            $('#view_train_name').text('Loading...');
            $('#view_train_provider').text('Loading...');
            $('#view_train_type').text('Loading...');
            $('#view_train_start').text('Loading...');
            $('#view_train_end').text('Loading...');
            $('#view_train_desc').text('Loading...');
            $('#view_train_doc').html('Loading...');

            $('#viewTrainingDetailModal').modal('show');

            $.ajax({
                url: `/trainingDetails/${tId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const t = response.trainingDetail;
                    if (t) {
                        $('#view_train_name').text(t.training_name || 'N/A');
                        $('#view_train_provider').text(t.training_provider || 'N/A');
                        $('#view_train_type').text(t.training_type || 'N/A');
                        $('#view_train_start').text(t.start_date || 'N/A');
                        $('#view_train_end').text(t.end_date || 'N/A');
                        $('#view_train_desc').text(t.description || 'No description provided');

                        if (t.document) {
                            $('#view_train_doc').html(`<a href="/${t.document}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Certificate Document</a>`);
                        } else {
                            $('#view_train_doc').html('<span class="text-muted">No document attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_train_name').text('Error loading details');
                }
            });
        });

        // Edit Training Detail
        $(document).on('click', '.edit-training-detail', function() {
            const tId = $(this).data('id');
            $.ajax({
                url: `/trainingDetails/${tId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const t = response.trainingDetail;
                    if (t) {
                        $('#training-detail-id').val(t.id);
                        $('#training_name').val(t.training_name);
                        $('#training_provider').val(t.training_provider);
                        $('#training_type').val(t.training_type);
                        $('#start_date').val(t.start_date);
                        $('#end_date').val(t.end_date);
                        $('#description').val(t.description);

                        if (t.document) {
                            $('#current-document-link-training').html(`<a href="/${t.document}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link-training').html('');
                        }

                        $('#training-form-title').text('Edit Training Detail #' + t.id);
                        toggleTrainingCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#trainingAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching training detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Training Detail
        $(document).on('click', '.delete-training-detail', function() {
            const tId = $(this).data('id');
            if (confirm('Are you sure you want to delete this training detail?')) {
                $.ajax({
                    url: `/trainingDetails/${tId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadTrainingDetails();
                    },
                    error: function(xhr) {
                        alert('Error deleting training detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
