<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4>Personal Documents</h4>
            <button type="button" class="btn btn-primary btn-sm" id="add-new-personal-document-btn" data-toggle="collapse" data-target="#collapseEight">Add New Document</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="personalDocumentsAccordion">
            <div class="accordion-item">

                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#personalDocumentsAccordion">
                    <div class="accordion-body">
                        <form id="personal-document-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="personal-document-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="form-group">
                                <label for="document_type">Document Type:</label>
                                <input type="text" name="document_type" id="document_type" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="document_file">Document File:</label>
                                <input type="file" name="document_file" id="document_file" class="form-control">
                                <span id="current-document-link"></span>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-personal-document-btn">Save Document</button>
                            <button type="button" class="btn btn-secondary" id="cancel-personal-document-edit-btn" data-toggle="collapse" data-target="#collapseEight">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Personal Documents in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Document Type</th>
                        <th>Document File</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="personal-documents-table-body">
                    @if(isset($users) && $users->personalDocuments->count() > 0)
                        @foreach($users->personalDocuments as $personalDocument)
                            <tr data-id="{{ $personalDocument->id }}">
                                <td>{{ $personalDocument->document_type }}</td>
                                <td>
                                    @if($personalDocument->document_file)
                                        <a href="{{ asset($personalDocument->document_file) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $personalDocument->description }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-personal-document" data-id="{{ $personalDocument->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-personal-document" data-id="{{ $personalDocument->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">No personal documents found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            const personalDocumentForm = $('#personal-document-form');
            const personalDocumentsAccordionCollapse = new bootstrap.Collapse($('#collapseEight'), { toggle: false });

            // Show form for adding new personal document
            $('#add-new-personal-document-btn').click(function() {
                personalDocumentForm[0].reset(); // Clear form
                $('#personal-document-id').val(''); // Clear ID for new entry
                $('#current-document-link').html(''); // Clear document link
                personalDocumentsAccordionCollapse.show(); // Show accordion
            });

            // Cancel button for form
            $('#cancel-personal-document-edit-btn').click(function() {
                personalDocumentsAccordionCollapse.hide(); // Hide accordion
            });

            // Save Personal Document (Add/Edit)
            personalDocumentForm.submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const personalDocumentId = $('#personal-document-id').val();
                const url = personalDocumentId ? `/personalDocuments/${personalDocumentId}` : '/personalDocuments';
                const method = personalDocumentId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

                if (personalDocumentId) {
                    formData.append('_method', 'PATCH'); // Spoof PATCH method for Laravel
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message);
                        personalDocumentsAccordionCollapse.hide();
                        location.reload(); // For simplicity, reload page. In production, update table dynamically.
                    },
                    error: function(xhr) {
                        alert('Error saving personal document: ' + xhr.responseText);
                    }
                });
            });

            // Edit Personal Document
            $(document).on('click', '.edit-personal-document', function() {
                const personalDocumentId = $(this).data('id');
                $.ajax({
                    url: `/personalDocuments/${personalDocumentId}/edit`, // Laravel's edit route returns data for form
                    type: 'GET',
                    success: function(response) {
                        $('#personal-document-id').val(response.id);
                        $('#document_type').val(response.document_type);
                        $('#description').val(response.description);
                        if (response.document_file) {
                            $('#current-document-link').html(`<a href="${response.document_file}" target="_blank">View Current Document</a>`);
                        } else {
                            $('#current-document-link').html('');
                        }
                        personalDocumentsAccordionCollapse.show(); // Show accordion
                    },
                    error: function(xhr) {
                        alert('Error fetching personal document: ' + xhr.responseText);
                    }
                });
            });

            // Delete Personal Document
            $(document).on('click', '.delete-personal-document', function() {
                if (confirm('Are you sure you want to delete this personal document?')) {
                    const personalDocumentId = $(this).data('id');
                    $.ajax({
                        url: `/personalDocuments/${personalDocumentId}`,
                        type: 'POST', // Laravel uses POST for DELETE with _method field
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                            $(`tr[data-id="${personalDocumentId}"]`).remove();
                            if ($('#personal-documents-table-body tr').length === 0) {
                                $('#personal-documents-table-body').html('<tr><td colspan="4" class="text-center">No personal documents found.</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting personal document: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
