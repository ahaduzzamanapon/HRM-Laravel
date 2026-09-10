<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Money-2 me-2"></i>Salary Increment Details</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="salaryIncrementsAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#salaryIncrementsAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="increment-form-title">Add / Edit Salary Increment</h5>
                        <form id="salary-increment-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="salary-increment-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="increment_date" class="fw-bold mb-1">Increment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="increment_date" id="increment_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="old_salaryy" class="fw-bold mb-1">Old Salary (৳)</label>
                                        <input type="number" name="old_salary" id="old_salaryy" class="form-control" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="old_grade_id" class="fw-bold mb-1">Old Grade</label>
                                        <select name="old_grade_id" id="old_grade_id" class="form-select form-control">
                                            <option value="">Select Old Grade</option>
                                            @foreach($salaryGrades as $grade)
                                                <option value="{{ $grade->id }}" {{ $users->salary_grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="new_salaryy" class="fw-bold mb-1">New Salary (৳) <span class="text-danger">*</span></label>
                                        <input type="number" name="new_salary" id="new_salaryy" class="form-control" step="0.01" required>
                                        <small id="new_salary_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="new_grade_id" class="fw-bold mb-1">New Grade</label>
                                        <select name="new_grade_id" id="new_grade_id" class="form-select form-control">
                                            <option value="">Select New Grade</option>
                                            @foreach($salaryGrades as $grade)
                                                <option value="{{ $grade->id }}" data-min="{{ $grade->starting_salary }}" data-max="{{ $grade->end_salary }}">{{ $grade->grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="increment_amount" class="fw-bold mb-1">Increment Amount (৳)</label>
                                        <input type="number" name="increment_amount" id="increment_amount" class="form-control" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="document_increment" class="fw-bold mb-1">Increment Approval Document:</label>
                                <input type="file" name="document" id="document_increment" class="form-control">
                                <small class="text-muted d-block mt-1" id="current-document-link-increment"></small>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-increment-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-salary-increment-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Increment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Salary Increment Details in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Increment Date</th>
                        <th>Old Salary</th>
                        <th>New Salary</th>
                        <th>Increment Amount</th>
                        <th>Old Grade</th>
                        <th>New Grade</th>
                        <th>Document</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="salary-increments-table-body">
                    @if(isset($users) && $users->salaryIncrements->count() > 0)
                        @foreach($users->salaryIncrements as $salaryIncrement)
                            <tr data-id="{{ $salaryIncrement->id }}">
                                <td class="fw-bold text-dark">{{ $salaryIncrement->increment_date }}</td>
                                <td>৳ {{ number_format($salaryIncrement->old_salary ?? 0, 2) }}</td>
                                <td class="fw-bold text-success">৳ {{ number_format($salaryIncrement->new_salary ?? 0, 2) }}</td>
                                <td class="fw-bold text-primary">+৳ {{ number_format($salaryIncrement->increment_amount ?? 0, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ $salaryIncrement->oldGrade->grade ?? 'N/A' }}</span></td>
                                <td><span class="badge bg-success">{{ $salaryIncrement->newGrade->grade ?? 'N/A' }}</span></td>
                                <td>
                                    @if($salaryIncrement->document)
                                        <a href="{{ asset($salaryIncrement->document) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-salary-increment" data-id="{{ $salaryIncrement->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-salary-increment" data-id="{{ $salaryIncrement->id }}" title="Edit Increment">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-salary-increment" data-id="{{ $salaryIncrement->id }}" title="Delete Increment">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No salary increment details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Salary Increment Modal -->
<div class="modal fade" id="viewSalaryIncrementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Money-2 me-2"></i>Salary Increment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 40%;">Increment Date:</th>
                            <td class="fw-bold text-primary" id="view_inc_date">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Old Salary:</th>
                            <td id="view_inc_old_salary">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">New Salary:</th>
                            <td class="fw-bold text-success" id="view_inc_new_salary">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Increment Amount:</th>
                            <td class="fw-bold text-primary" id="view_inc_amount">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Old Grade:</th>
                            <td id="view_inc_old_grade">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">New Grade:</th>
                            <td id="view_inc_new_grade">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Increment Document:</th>
                            <td id="view_inc_doc">Loading...</td>
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
        const salaryIncrementForm = $('#salary-increment-form');

        function toggleIncrementCollapse(show) {
            if (show) {
                $('#collapseSix').collapse('show');
            } else {
                $('#collapseSix').collapse('hide');
            }
        }

        function loadSalaryIncrements() {
            $.ajax({
                url: `/salaryIncrements/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.salaryIncrement && res.salaryIncrement.length > 0) {
                        res.salaryIncrement.forEach(s => {
                            let docHtml = s.document 
                                ? `<a href="/${s.document}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${s.id}">
                                <td class="fw-bold text-dark">${s.increment_date || 'N/A'}</td>
                                <td>৳ ${parseFloat(s.old_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                <td class="fw-bold text-success">৳ ${parseFloat(s.new_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                <td class="fw-bold text-primary">+৳ ${parseFloat(s.increment_amount || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                <td><span class="badge bg-secondary">${s.old_grade ? s.old_grade.grade : 'N/A'}</span></td>
                                <td><span class="badge bg-success">${s.new_grade ? s.new_grade.grade : 'N/A'}</span></td>
                                <td>${docHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-salary-increment" data-id="${s.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-salary-increment" data-id="${s.id}" title="Edit Increment">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-salary-increment" data-id="${s.id}" title="Delete Increment">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="8" class="text-center py-4 text-muted">No salary increment details found.</td></tr>';
                    }
                    $('#salary-increments-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading salary increment table:', xhr.responseText);
                }
            });
        }

        loadSalaryIncrements();

        $('#cancel-increment-btn').click(function() {
            salaryIncrementForm[0].reset();
            $('#salary-increment-id').val('');
            $('#current-document-link-increment').html('');
            $('#increment-form-title').text('Add / Edit Salary Increment');
            toggleIncrementCollapse(false);
        });

        salaryIncrementForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const sId = $('#salary-increment-id').val();
            const url = sId ? `/salaryIncrements/${sId}` : '/salaryIncrements';

            if (sId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadSalaryIncrements();
                    alert(response.message || 'Increment saved successfully.');
                    salaryIncrementForm[0].reset();
                    $('#salary-increment-id').val('');
                    $('#current-document-link-increment').html('');
                    $('#increment-form-title').text('Add / Edit Salary Increment');
                    toggleIncrementCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving salary increment: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Salary Increment Modal
        $(document).on('click', '.view-salary-increment', function() {
            const sId = $(this).data('id');
            $('#view_inc_date').text('Loading...');
            $('#view_inc_old_salary').text('Loading...');
            $('#view_inc_new_salary').text('Loading...');
            $('#view_inc_amount').text('Loading...');
            $('#view_inc_old_grade').text('Loading...');
            $('#view_inc_new_grade').text('Loading...');
            $('#view_inc_doc').html('Loading...');

            $('#viewSalaryIncrementModal').modal('show');

            $.ajax({
                url: `/salaryIncrements/${sId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const s = response.salaryIncrement;
                    if (s) {
                        $('#view_inc_date').text(s.increment_date || 'N/A');
                        $('#view_inc_old_salary').text('৳ ' + parseFloat(s.old_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2}));
                        $('#view_inc_new_salary').text('৳ ' + parseFloat(s.new_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2}));
                        $('#view_inc_amount').text('+ ৳ ' + parseFloat(s.increment_amount || 0).toLocaleString('en-US', {minimumFractionDigits:2}));
                        $('#view_inc_old_grade').text(s.old_grade ? s.old_grade.grade : 'N/A');
                        $('#view_inc_new_grade').text(s.new_grade ? s.new_grade.grade : 'N/A');

                        if (s.document) {
                            $('#view_inc_doc').html(`<a href="/${s.document}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Document File</a>`);
                        } else {
                            $('#view_inc_doc').html('<span class="text-muted">No document attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_inc_date').text('Error loading details');
                }
            });
        });

        // Edit Salary Increment
        $(document).on('click', '.edit-salary-increment', function() {
            const sId = $(this).data('id');
            $.ajax({
                url: `/salaryIncrements/${sId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const s = response.salaryIncrement;
                    if (s) {
                        $('#salary-increment-id').val(s.id);
                        $('#increment_date').val(s.increment_date);
                        $('#old_salaryy').val(s.old_salary);
                        $('#new_salaryy').val(s.new_salary);
                        $('#increment_amount').val(s.increment_amount);
                        $('#old_grade_id').val(s.old_grade_id);
                        $('#new_grade_id').val(s.new_grade_id);

                        if (s.document) {
                            $('#current-document-link-increment').html(`<a href="/${s.document}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link-increment').html('');
                        }

                        $('#increment-form-title').text('Edit Salary Increment #' + s.id);
                        toggleIncrementCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#salaryIncrementsAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching salary increment: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Salary Increment
        $(document).on('click', '.delete-salary-increment', function() {
            const sId = $(this).data('id');
            if (confirm('Are you sure you want to delete this salary increment?')) {
                $.ajax({
                    url: `/salaryIncrements/${sId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadSalaryIncrements();
                    },
                    error: function(xhr) {
                        alert('Error deleting salary increment: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
