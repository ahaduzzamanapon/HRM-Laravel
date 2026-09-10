<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Arrow-Up me-2"></i>Promotion Details</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="promotionDetailsAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#promotionDetailsAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="promotion-form-title">Add / Edit Promotion Detail</h5>
                        <form id="promotion-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="promotion-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promotion_date" class="fw-bold mb-1">Promotion Date <span class="text-danger">*</span></label>
                                        <input type="date" name="promotion_date" id="promotion_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_designation" class="fw-bold mb-1">Old Designation</label>
                                        {!! Form::select('old_designation', $designations, $users->designation_id, ['class' => 'form-select form-control', 'placeholder' => 'Select Old Designation', 'id' => 'old_designation']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_designation" class="fw-bold mb-1">New Designation <span class="text-danger">*</span></label>
                                        {!! Form::select('new_designation', $designations, null, ['class' => 'form-select form-control', 'placeholder' => 'Select New Designation', 'id' => 'new_designation', 'required' => 'required']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="pay_grade_change" id="pay_grade_change" class="form-check-input" value="1">
                                        <label for="pay_grade_change" class="form-check-label fw-bold">Pay Grade Change Required</label>
                                    </div>
                                </div>
                            </div>

                            <div id="pay_grade_change_fields" class="mt-3 p-3 bg-white border rounded" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="new_grade_id_promotion" class="fw-bold mb-1">New Grade</label>
                                            <select name="new_grade_id" id="new_grade_id_promotion" class="form-select form-control">
                                                <option value="">Select New Grade</option>
                                                @foreach($salaryGrades as $grade)
                                                    <option value="{{ $grade->id }}" data-min="{{ $grade->starting_salary }}" data-max="{{ $grade->end_salary }}">{{ $grade->grade }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="new_salary_promotion" class="fw-bold mb-1">New Salary (৳)</label>
                                            <input type="number" name="new_salary" id="new_salary_promotion" class="form-control" step="0.01">
                                            <small id="new_salary_error_promotion" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="increment_amount_promotion" class="fw-bold mb-1">Increment Amount (৳)</label>
                                            <input type="number" name="increment_amount" id="increment_amount_promotion" class="form-control" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="document_promotion" class="fw-bold mb-1">Promotion Letter / Document:</label>
                                <input type="file" name="document" id="document_promotion" class="form-control">
                                <small class="text-muted d-block mt-1" id="current-document-link-promotion"></small>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-promotion-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-promotion-detail-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Promotion
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Promotion Details in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Promotion Date</th>
                        <th>New Designation</th>
                        <th>Old Designation</th>
                        <th>Pay Grade Change</th>
                        <th>New Salary</th>
                        <th>Document</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="promotion-details-table-body">
                    @if(isset($users) && $users->promotionDetails->count() > 0)
                        @foreach($users->promotionDetails as $promotionDetail)
                            <tr data-id="{{ $promotionDetail->id }}">
                                <td class="fw-bold text-dark">{{ $promotionDetail->promotion_date }}</td>
                                <td><span class="badge bg-success">{{ $promotionDetail->new_designation }}</span></td>
                                <td>{{ $promotionDetail->old_designation ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $promotionDetail->pay_grade_change ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ $promotionDetail->pay_grade_change ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="fw-bold">৳ {{ number_format($promotionDetail->new_salary ?? 0, 2) }}</td>
                                <td>
                                    @if($promotionDetail->document)
                                        <a href="{{ asset($promotionDetail->document) }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fa fa-file-text-o me-1"></i> View File
                                        </a>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-promotion-detail" data-id="{{ $promotionDetail->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-promotion-detail" data-id="{{ $promotionDetail->id }}" title="Edit Promotion">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="{{ $promotionDetail->id }}" title="Delete Promotion">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No promotion details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Promotion Detail Modal -->
<div class="modal fade" id="viewPromotionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Arrow-Up me-2"></i>Promotion Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 40%;">Promotion Date:</th>
                            <td class="fw-bold text-primary" id="view_prom_date">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">New Designation:</th>
                            <td id="view_prom_new_desi">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Old Designation:</th>
                            <td id="view_prom_old_desi">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Pay Grade Change:</th>
                            <td id="view_prom_grade_change">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">New Salary:</th>
                            <td id="view_prom_new_salary">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Promotion Document:</th>
                            <td id="view_prom_doc">Loading...</td>
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
        const promotionForm = $('#promotion-detail-form');

        function togglePromotionCollapse(show) {
            if (show) {
                $('#collapseFive').collapse('show');
            } else {
                $('#collapseFive').collapse('hide');
            }
        }

        $('#pay_grade_change').on('change', function() {
            if ($(this).is(':checked')) {
                $('#pay_grade_change_fields').slideDown();
            } else {
                $('#pay_grade_change_fields').slideUp();
            }
        });

        function loadPromotionDetails() {
            $.ajax({
                url: `/promotionDetails/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.promotionDetail && res.promotionDetail.length > 0) {
                        res.promotionDetail.forEach(p => {
                            let docHtml = p.document 
                                ? `<a href="/${p.document}" target="_blank" class="badge bg-info text-decoration-none"><i class="fa fa-file-text-o me-1"></i> View File</a>` 
                                : '<span class="text-muted small">N/A</span>';

                            tableData += `<tr data-id="${p.id}">
                                <td class="fw-bold text-dark">${p.promotion_date || 'N/A'}</td>
                                <td><span class="badge bg-success">${p.new_designation || 'N/A'}</span></td>
                                <td>${p.old_designation || 'N/A'}</td>
                                <td><span class="badge ${p.pay_grade_change ? 'bg-primary' : 'bg-secondary'}">${p.pay_grade_change ? 'Yes' : 'No'}</span></td>
                                <td class="fw-bold">৳ ${parseFloat(p.new_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2})}</td>
                                <td>${docHtml}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-promotion-detail" data-id="${p.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-promotion-detail" data-id="${p.id}" title="Edit Promotion">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="${p.id}" title="Delete Promotion">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="7" class="text-center py-4 text-muted">No promotion details found.</td></tr>';
                    }
                    $('#promotion-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading promotion table:', xhr.responseText);
                }
            });
        }

        loadPromotionDetails();

        $('#cancel-promotion-btn').click(function() {
            promotionForm[0].reset();
            $('#promotion-detail-id').val('');
            $('#current-document-link-promotion').html('');
            $('#pay_grade_change_fields').hide();
            $('#promotion-form-title').text('Add / Edit Promotion Detail');
            togglePromotionCollapse(false);
        });

        promotionForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.set('pay_grade_change', $('#pay_grade_change').is(':checked') ? 1 : 0);

            const pId = $('#promotion-detail-id').val();
            const url = pId ? `/promotionDetails/${pId}` : '/promotionDetails';

            if (pId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadPromotionDetails();
                    alert(response.message || 'Promotion saved successfully.');
                    promotionForm[0].reset();
                    $('#promotion-detail-id').val('');
                    $('#current-document-link-promotion').html('');
                    $('#pay_grade_change_fields').hide();
                    $('#promotion-form-title').text('Add / Edit Promotion Detail');
                    togglePromotionCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving promotion detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Promotion Modal
        $(document).on('click', '.view-promotion-detail', function() {
            const pId = $(this).data('id');
            $('#view_prom_date').text('Loading...');
            $('#view_prom_new_desi').text('Loading...');
            $('#view_prom_old_desi').text('Loading...');
            $('#view_prom_grade_change').text('Loading...');
            $('#view_prom_new_salary').text('Loading...');
            $('#view_prom_doc').html('Loading...');

            $('#viewPromotionModal').modal('show');

            $.ajax({
                url: `/promotionDetails/${pId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const p = response.promotionDetail;
                    if (p) {
                        $('#view_prom_date').text(p.promotion_date || 'N/A');
                        $('#view_prom_new_desi').text(p.new_designation || 'N/A');
                        $('#view_prom_old_desi').text(p.old_designation || 'N/A');
                        $('#view_prom_grade_change').text(p.pay_grade_change ? 'Yes' : 'No');
                        $('#view_prom_new_salary').text('৳ ' + parseFloat(p.new_salary || 0).toLocaleString('en-US', {minimumFractionDigits:2}));

                        if (p.document) {
                            $('#view_prom_doc').html(`<a href="/${p.document}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-external-link me-1"></i> Open Document File</a>`);
                        } else {
                            $('#view_prom_doc').html('<span class="text-muted">No document attached</span>');
                        }
                    }
                },
                error: function(xhr) {
                    $('#view_prom_date').text('Error loading details');
                }
            });
        });

        // Edit Promotion
        $(document).on('click', '.edit-promotion-detail', function() {
            const pId = $(this).data('id');
            $.ajax({
                url: `/promotionDetails/${pId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const p = response.promotionDetail;
                    if (p) {
                        $('#promotion-detail-id').val(p.id);
                        $('#promotion_date').val(p.promotion_date);
                        $('#old_designation').val(p.old_designation);
                        $('#new_designation').val(p.new_designation);
                        $('#pay_grade_change').prop('checked', p.pay_grade_change == 1);
                        $('#new_salary_promotion').val(p.new_salary);

                        if (p.pay_grade_change == 1) {
                            $('#pay_grade_change_fields').show();
                        } else {
                            $('#pay_grade_change_fields').hide();
                        }

                        if (p.document) {
                            $('#current-document-link-promotion').html(`<a href="/${p.document}" target="_blank" class="text-info fw-bold"><i class="fa fa-file-text-o me-1"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link-promotion').html('');
                        }

                        $('#promotion-form-title').text('Edit Promotion Detail #' + p.id);
                        togglePromotionCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#promotionDetailsAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching promotion detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Promotion
        $(document).on('click', '.delete-promotion-detail', function() {
            const pId = $(this).data('id');
            if (confirm('Are you sure you want to delete this promotion detail?')) {
                $.ajax({
                    url: `/promotionDetails/${pId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadPromotionDetails();
                    },
                    error: function(xhr) {
                        alert('Error deleting promotion detail: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
