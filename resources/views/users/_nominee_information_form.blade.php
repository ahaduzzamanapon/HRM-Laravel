<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Add-User me-2"></i>Nominee Information</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="nomineeInformationAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#nomineeInformationAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="nominee-form-title">Add / Edit Nominee Information</h5>
                        <form id="nominee-information-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="nominee-information-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nominee_name" class="fw-bold mb-1">Nominee Name <span class="text-danger">*</span></label>
                                        <input type="text" name="nominee_name" id="nominee_name" class="form-control" required placeholder="e.g. Jane Doe">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="relation" class="fw-bold mb-1">Relation <span class="text-danger">*</span></label>
                                        <input type="text" name="relation" id="relation" class="form-control" required placeholder="e.g. Spouse / Brother / Father">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="voter_id" class="fw-bold mb-1">Voter ID / NID</label>
                                        <input type="text" name="voter_id" id="voter_id" class="form-control" placeholder="e.g. 1928374650">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="percentage" class="fw-bold mb-1">Share Percentage (%)</label>
                                        <input type="number" name="percentage" id="percentage" class="form-control" step="0.01" min="0" max="100" placeholder="e.g. 100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="photo" class="fw-bold mb-1">Photo / Document:</label>
                                        <input type="file" name="photo" id="photo" class="form-control">
                                        <small class="text-muted d-block mt-1" id="current-photo-link"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-nominee-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-nominee-information-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Nominee
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Nominee Information in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nominee Name</th>
                        <th>Relation</th>
                        <th>Voter ID / NID</th>
                        <th>Percentage</th>
                        <th>Photo</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="nominee-information-table-body">
                    @if(isset($users) && $users->nomineeInformation->count() > 0)
                        @foreach($users->nomineeInformation as $nomineeInformation)
                            <tr data-id="{{ $nomineeInformation->id }}">
                                <td class="fw-bold text-dark">{{ $nomineeInformation->nominee_name }}</td>
                                <td>{{ $nomineeInformation->relation }}</td>
                                <td>{{ $nomineeInformation->voter_id ?? 'N/A' }}</td>
                                <td><span class="badge bg-success">{{ $nomineeInformation->percentage }}%</span></td>
                                <td>
                                    @if($nomineeInformation->photo)
                                        <a href="{{ asset($nomineeInformation->photo) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-image me-1"></i> View Photo
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-nominee-information" data-id="{{ $nomineeInformation->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-nominee-information" data-id="{{ $nomineeInformation->id }}" title="Edit Nominee">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-nominee-information" data-id="{{ $nomineeInformation->id }}" title="Delete Nominee">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No nominee information found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Nominee Modal -->
<div class="modal fade" id="viewNomineeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Add-User me-2"></i>Nominee Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Nominee Name:</th>
                            <td class="fw-bold text-primary" id="view_nominee_name">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Relation:</th>
                            <td id="view_nominee_relation">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Voter ID / NID:</th>
                            <td id="view_nominee_voter">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Share Percentage:</th>
                            <td id="view_nominee_percentage">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Photo / Attachment:</th>
                            <td id="view_nominee_photo">Loading...</td>
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
        const nomineeInformationForm = $('#nominee-information-form');

        function toggleNomineeCollapse(show) {
            if (show) {
                $('#collapseFour').collapse('show');
            } else {
                $('#collapseFour').collapse('hide');
            }
        }

        function loadNomineeInformation() {
            $.ajax({
                url: `/nomineeInformation/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.nomineeInformation && res.nomineeInformation.length > 0) {
                        res.nomineeInformation.forEach(n => {
                            let photoHtml = n.photo 
                                ? `<a href="/${n.photo}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-image me-1"></i> View Photo</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${n.id}">
                                <td class="fw-bold text-dark">${n.nominee_name || 'N/A'}</td>
                                <td>${n.relation || 'N/A'}</td>
                                <td>${n.voter_id || 'N/A'}</td>
                                <td><span class="badge bg-success">${n.percentage || 0}%</span></td>
                                <td>${photoHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-nominee-information" data-id="${n.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-nominee-information" data-id="${n.id}" title="Edit Nominee">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-nominee-information" data-id="${n.id}" title="Delete Nominee">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="6" class="text-center py-4 text-muted">No nominee information found.</td></tr>';
                    }
                    $('#nominee-information-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading nominee information:', xhr.responseText);
                }
            });
        }

        loadNomineeInformation();

        $('#cancel-nominee-btn').click(function() {
            nomineeInformationForm[0].reset();
            $('#nominee-information-id').val('');
            $('#current-photo-link').html('');
            $('#nominee-form-title').text('Add / Edit Nominee Information');
            toggleNomineeCollapse(false);
        });

        nomineeInformationForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const nId = $('#nominee-information-id').val();
            const url = nId ? `/nomineeInformation/${nId}` : '/nomineeInformation';

            if (nId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadNomineeInformation();
                    if(response.error){
                        alert(response.message || 'Error saving nominee information');
                        return;
                    }
                    alert(response.message || 'Nominee saved successfully');
                    nomineeInformationForm[0].reset();
                    $('#nominee-information-id').val('');
                    $('#current-photo-link').html('');
                    $('#nominee-form-title').text('Add / Edit Nominee Information');
                    toggleNomineeCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving nominee information: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Nominee Details Modal
        $(document).on('click', '.view-nominee-information', function() {
            const nId = $(this).data('id');
            $('#view_nominee_name').text('Loading...');
            $('#view_nominee_relation').text('Loading...');
            $('#view_nominee_voter').text('Loading...');
            $('#view_nominee_percentage').text('Loading...');
            $('#view_nominee_photo').html('Loading...');

            $('#viewNomineeModal').modal('show');

            $.ajax({
                url: `/nomineeInformation/${nId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const n = response.nomineeInformation;
                    if (n) {
                        $('#view_nominee_name').text(n.nominee_name || 'N/A');
                        $('#view_nominee_relation').text(n.relation || 'N/A');
                        $('#view_nominee_voter').text(n.voter_id || 'N/A');
                        $('#view_nominee_percentage').text((n.percentage || '0') + '%');

                        if (n.photo) {
                            $('#view_nominee_photo').html(`<a href="/${n.photo}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-image me-1"></i> Open Photo File</a>`);
                        } else {
                            $('#view_nominee_photo').html('<span class="text-muted">No photo attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_nominee_name').text('Error loading details');
                }
            });
        });

        // Edit Nominee
        $(document).on('click', '.edit-nominee-information', function() {
            const nId = $(this).data('id');
            $.ajax({
                url: `/nomineeInformation/${nId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const n = response.nomineeInformation;
                    if (n) {
                        $('#nominee-information-id').val(n.id);
                        $('#nominee_name').val(n.nominee_name);
                        $('#relation').val(n.relation);
                        $('#voter_id').val(n.voter_id);
                        $('#percentage').val(n.percentage);

                        if (n.photo) {
                            $('#current-photo-link').html(`<a href="/${n.photo}" target="_blank" class="text-info fw-bold"><i class="fa fa-image me-1"></i> View Current Photo</a>`);
                        } else {
                            $('#current-photo-link').html('');
                        }

                        $('#nominee-form-title').text('Edit Nominee Information #' + n.id);
                        toggleNomineeCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#nomineeInformationAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching nominee information: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Nominee
        $(document).on('click', '.delete-nominee-information', function() {
            const nId = $(this).data('id');
            if (confirm('Are you sure you want to delete this nominee information?')) {
                $.ajax({
                    url: `/nomineeInformation/${nId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadNomineeInformation();
                    },
                    error: function(xhr) {
                        alert('Error deleting nominee information: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
