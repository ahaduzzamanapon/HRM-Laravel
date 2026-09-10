<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Student-Hat me-2"></i>Educational Qualifications</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="educationalQualificationsAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#educationalQualificationsAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="edu-form-title">Add / Edit Educational Qualification</h5>
                        <form id="educational-qualification-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="educational-qualification-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="degree" class="fw-bold mb-1">Degree / Qualification <span class="text-danger">*</span></label>
                                        <input type="text" name="degree" id="degree" class="form-control" required placeholder="e.g. B.Sc in Computer Science">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="institution" class="fw-bold mb-1">Institution / University <span class="text-danger">*</span></label>
                                        <input type="text" name="institution" id="institution" class="form-control" required placeholder="e.g. BUET / Dhaka University">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passing_year" class="fw-bold mb-1">Passing Year</label>
                                        <input type="text" name="passing_year" id="passing_year" class="form-control" placeholder="e.g. 2024">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="grade" class="fw-bold mb-1">Grade / CGPA</label>
                                        <input type="text" name="grade" id="grade" class="form-control" placeholder="e.g. 3.85 / 1st Class">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="document" class="fw-bold mb-1">Certificate / Document File:</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <small class="text-muted d-block mt-1" id="current-document-link"></small>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-edu-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-educational-qualification-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Qualification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Educational Qualifications in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Passing Year</th>
                        <th>Grade</th>
                        <th>Document</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="educational-qualifications-table-body">
                    @if(isset($users) && $users->educationalQualifications->count() > 0)
                        @foreach($users->educationalQualifications as $educationalQualification)
                            <tr data-id="{{ $educationalQualification->id }}">
                                <td class="fw-bold text-dark">{{ $educationalQualification->degree }}</td>
                                <td>{{ $educationalQualification->institution }}</td>
                                <td>{{ $educationalQualification->passing_year ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ $educationalQualification->grade ?? 'N/A' }}</span></td>
                                <td>
                                    @if($educationalQualification->document)
                                        <a href="{{ asset($educationalQualification->document) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-educational-qualification" data-id="{{ $educationalQualification->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-educational-qualification" data-id="{{ $educationalQualification->id }}" title="Edit Qualification">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-educational-qualification" data-id="{{ $educationalQualification->id }}" title="Delete Qualification">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No educational qualifications found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Educational Qualification Modal -->
<div class="modal fade" id="viewEducationalQualificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Student-Hat me-2"></i>Educational Qualification Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Degree / Qualification:</th>
                            <td class="fw-bold text-primary" id="view_edu_degree">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Institution:</th>
                            <td id="view_edu_institution">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Passing Year:</th>
                            <td id="view_edu_passing_year">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Grade / CGPA:</th>
                            <td id="view_edu_grade">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Certificate Document:</th>
                            <td id="view_edu_document">Loading...</td>
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
        const educationalQualificationForm = $('#educational-qualification-form');

        function toggleEduCollapse(show) {
            if (show) {
                $('#collapseThree').collapse('show');
            } else {
                $('#collapseThree').collapse('hide');
            }
        }

        function loadEducationalQualifications() {
            $.ajax({
                url: `/educationalQualifications/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';

                    if (res.educationalQualification && res.educationalQualification.length > 0) {
                        res.educationalQualification.forEach(edu => {
                            let docHtml = edu.document 
                                ? `<a href="/${edu.document}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${edu.id}">
                                <td class="fw-bold text-dark">${edu.degree || 'N/A'}</td>
                                <td>${edu.institution || 'N/A'}</td>
                                <td>${edu.passing_year || 'N/A'}</td>
                                <td><span class="badge bg-secondary">${edu.grade || 'N/A'}</span></td>
                                <td>${docHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-educational-qualification" data-id="${edu.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-educational-qualification" data-id="${edu.id}" title="Edit Qualification">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-educational-qualification" data-id="${edu.id}" title="Delete Qualification">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="6" class="text-center py-4 text-muted">No educational qualifications found.</td></tr>';
                    }

                    $('#educational-qualifications-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading educational qualifications:', xhr.responseText);
                }
            });
        }

        loadEducationalQualifications();

        $('#cancel-edu-btn').click(function() {
            educationalQualificationForm[0].reset();
            $('#educational-qualification-id').val('');
            $('#current-document-link').html('');
            $('#edu-form-title').text('Add / Edit Educational Qualification');
            toggleEduCollapse(false);
        });

        educationalQualificationForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const eduId = $('#educational-qualification-id').val();
            const url = eduId ? `/educationalQualifications/${eduId}` : '/educationalQualifications';

            if (eduId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadEducationalQualifications();
                    if(response.error){
                        alert(response.message || 'Error saving record');
                        return false;
                    }
                    alert(response.message || 'Saved successfully');
                    educationalQualificationForm[0].reset();
                    $('#educational-qualification-id').val('');
                    $('#current-document-link').html('');
                    $('#edu-form-title').text('Add / Edit Educational Qualification');
                    toggleEduCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving educational qualification: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Educational Qualification Modal
        $(document).on('click', '.view-educational-qualification', function() {
            const eduId = $(this).data('id');
            
            $('#view_edu_degree').text('Loading...');
            $('#view_edu_institution').text('Loading...');
            $('#view_edu_passing_year').text('Loading...');
            $('#view_edu_grade').text('Loading...');
            $('#view_edu_document').html('Loading...');

            $('#viewEducationalQualificationModal').modal('show');

            $.ajax({
                url: `/educationalQualifications/${eduId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const edu = response.educationalQualification;
                    if (edu) {
                        $('#view_edu_degree').text(edu.degree || 'N/A');
                        $('#view_edu_institution').text(edu.institution || 'N/A');
                        $('#view_edu_passing_year').text(edu.passing_year || 'N/A');
                        $('#view_edu_grade').text(edu.grade || 'N/A');

                        if (edu.document) {
                            $('#view_edu_document').html(`<a href="/${edu.document}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Certificate Document</a>`);
                        } else {
                            $('#view_edu_document').html('<span class="text-muted">No document attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_edu_degree').text('Error loading details');
                }
            });
        });

        // Edit Educational Qualification
        $(document).on('click', '.edit-educational-qualification', function() {
            const eduId = $(this).data('id');

            $.ajax({
                url: `/educationalQualifications/${eduId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const edu = response.educationalQualification;
                    if (edu) {
                        $('#educational-qualification-id').val(edu.id);
                        $('#degree').val(edu.degree);
                        $('#institution').val(edu.institution);
                        $('#passing_year').val(edu.passing_year);
                        $('#grade').val(edu.grade);

                        if (edu.document) {
                            $('#current-document-link').html(`<a href="/${edu.document}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link').html('');
                        }

                        $('#edu-form-title').text('Edit Educational Qualification #' + edu.id);
                        toggleEduCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#educationalQualificationsAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching educational qualification: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Educational Qualification
        $(document).on('click', '.delete-educational-qualification', function() {
            const eduId = $(this).data('id');
            if (!confirm('Are you sure you want to delete this educational qualification?')) return;

            $.ajax({
                url: `/educationalQualifications/${eduId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message || 'Deleted successfully.');
                    loadEducationalQualifications();
                },
                error: function(xhr) {
                    alert('Error deleting educational qualification: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

    });
</script>
@endpush
