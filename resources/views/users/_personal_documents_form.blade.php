<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Folder-WithDocument me-2"></i>Personal Documents</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                <i class="im im-icon-Add me-1"></i> Add New Document
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="personalDocumentsAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#personalDocumentsAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="doc-form-title">Add / Edit Personal Document</h5>
                        <form id="personal-document-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="personal-document-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document_type" class="fw-bold mb-1">Document Type <span class="text-danger">*</span></label>
                                        <input type="text" name="document_type" id="document_type" class="form-control" required placeholder="e.g. Passport / NID / Academic Transcript">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document_file_personal" class="fw-bold mb-1">Document File <span class="text-danger">*</span></label>
                                        <input type="file" name="document_file" id="document_file_personal" class="form-control">
                                        <small class="text-muted d-block mt-1" id="current-document-link-personal"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="descriptions" class="fw-bold mb-1">Description / Notes:</label>
                                <textarea name="description" id="descriptions" class="form-control" rows="3" placeholder="Brief description of the document..."></textarea>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-doc-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-personal-document-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Personal Documents in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Document Type</th>
                        <th>Description</th>
                        <th>Document File</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="personal-documents-table-body">
                    @if(isset($users) && $users->personalDocuments->count() > 0)
                        @foreach($users->personalDocuments as $personalDocument)
                            <tr data-id="{{ $personalDocument->id }}">
                                <td class="fw-bold text-dark">{{ $personalDocument->document_type }}</td>
                                <td>{{ $personalDocument->description ?? 'N/A' }}</td>
                                <td>
                                    @if($personalDocument->document_file)
                                        <a href="{{ asset($personalDocument->document_file) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-personal-document" data-id="{{ $personalDocument->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-personal-document" data-id="{{ $personalDocument->id }}" title="Edit Document">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-personal-document" data-id="{{ $personalDocument->id }}" title="Delete Document">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No personal documents found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Personal Document Modal -->
<div class="modal fade" id="viewPersonalDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Folder-WithDocument me-2"></i>Personal Document Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Document Type:</th>
                            <td class="fw-bold text-primary" id="view_doc_type">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Description:</th>
                            <td id="view_doc_desc">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Document File:</th>
                            <td id="view_doc_file">Loading...</td>
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
        const personalDocumentForm = $('#personal-document-form');

        function toggleDocCollapse(show) {
            if (show) {
                $('#collapseEight').collapse('show');
            } else {
                $('#collapseEight').collapse('hide');
            }
        }

        function loadPersonalDocuments() {
            $.ajax({
                url: `/personalDocuments/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.personalDocuments && res.personalDocuments.length > 0) {
                        res.personalDocuments.forEach(doc => {
                            let fileHtml = doc.document_file 
                                ? `<a href="/${doc.document_file}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${doc.id}">
                                <td class="fw-bold text-dark">${doc.document_type || 'N/A'}</td>
                                <td>${doc.description || 'N/A'}</td>
                                <td>${fileHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-personal-document" data-id="${doc.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-personal-document" data-id="${doc.id}" title="Edit Document">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-personal-document" data-id="${doc.id}" title="Delete Document">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="4" class="text-center py-4 text-muted">No personal documents found.</td></tr>';
                    }
                    $('#personal-documents-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading personal documents:', xhr.responseText);
                }
            });
        }

        loadPersonalDocuments();

        $('#cancel-doc-btn').click(function() {
            personalDocumentForm[0].reset();
            $('#personal-document-id').val('');
            $('#current-document-link-personal').html('');
            $('#doc-form-title').text('Add / Edit Personal Document');
            toggleDocCollapse(false);
        });

        personalDocumentForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const docId = $('#personal-document-id').val();
            const url = docId ? `/personalDocuments/${docId}` : '/personalDocuments';

            if (docId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadPersonalDocuments();
                    alert(response.message || 'Document saved successfully.');
                    personalDocumentForm[0].reset();
                    $('#personal-document-id').val('');
                    $('#current-document-link-personal').html('');
                    $('#doc-form-title').text('Add / Edit Personal Document');
                    toggleDocCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving personal document: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Document Modal
        $(document).on('click', '.view-personal-document', function() {
            const docId = $(this).data('id');
            $('#view_doc_type').text('Loading...');
            $('#view_doc_desc').text('Loading...');
            $('#view_doc_file').html('Loading...');

            $('#viewPersonalDocumentModal').modal('show');

            $.ajax({
                url: `/personalDocuments/${docId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const doc = response.personalDocument;
                    if (doc) {
                        $('#view_doc_type').text(doc.document_type || 'N/A');
                        $('#view_doc_desc').text(doc.description || 'No description provided');

                        if (doc.document_file) {
                            $('#view_doc_file').html(`<a href="/${doc.document_file}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Document File</a>`);
                        } else {
                            $('#view_doc_file').html('<span class="text-muted">No document file attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_doc_type').text('Error loading details');
                }
            });
        });

        // Edit Document
        $(document).on('click', '.edit-personal-document', function() {
            const docId = $(this).data('id');
            $.ajax({
                url: `/personalDocuments/${docId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const doc = response.personalDocument;
                    if (doc) {
                        $('#personal-document-id').val(doc.id);
                        $('#document_type').val(doc.document_type);
                        $('#descriptions').val(doc.description);

                        if (doc.document_file) {
                            $('#current-document-link-personal').html(`<a href="/${doc.document_file}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current File</a>`);
                        } else {
                            $('#current-document-link-personal').html('');
                        }

                        $('#doc-form-title').text('Edit Personal Document #' + doc.id);
                        toggleDocCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#personalDocumentsAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching personal document: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Document
        $(document).on('click', '.delete-personal-document', function() {
            const docId = $(this).data('id');
            if (confirm('Are you sure you want to delete this personal document?')) {
                $.ajax({
                    url: `/personalDocuments/${docId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadPersonalDocuments();
                    },
                    error: function(xhr) {
                        alert('Error deleting personal document: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
