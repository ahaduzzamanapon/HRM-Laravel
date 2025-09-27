<div class="row">
    <div class="col-md-12">
        <!-- Add New Promotion Button (moved to top right) -->
        <div class="d-flex justify-content-between mb-3">
            <h4>Promotion Details</h4>
            <button class="btn btn-primary btn-sm  col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive"><i class="im im-icon-Add"></i> Add New</button>
        </div>
        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="promotionDetailsAccordion">
            <div class="accordion-item">
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#promotionDetailsAccordion">
                    <div class="accordion-body">
                        <form id="promotion-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="promotion-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promotion_date">Promotion Date:</label>
                                        <input type="date" name="promotion_date" id="promotion_date" class="form-control">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_designation">Old Designation:</label>
                                        {!! Form::select('old_designation', $designations, $users->designation_id, ['class' => 'form-control', 'placeholder' => 'Select Old Designation', 'id' => 'old_designation']) !!}
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_designation">New Designation:</label>
                                        {!! Form::select('new_designation', $designations, null, ['class' => 'form-control', 'placeholder' => 'Select New Designation', 'id' => 'new_designation']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pay_grade_change">Pay Grade Change:</label>
                                        <input type="checkbox" name="pay_grade_change" id="pay_grade_change"  class="form-check-input">
                                    </div>
                                </div>
                            </div>
                            <div id="pay_grade_change_fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_grade_id">New Grade:</label>
                                            <select name="new_grade_id" id="new_grade_id_promotion" class="form-control">
                                                <option value="">Select New Grade</option>
                                                @foreach($salaryGrades as $grade)
                                                    <option value="{{ $grade->id }}" data-min="{{ $grade->starting_salary }}" data-max="{{ $grade->end_salary }}">{{ $grade->grade }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_salary">New Salary:</label>
                                            <input type="number" name="new_salary" id="new_salary_promotion" class="form-control" step="0.01">
                                            <small id="new_salary_error_promotion" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="increment_amount">Increment Amount:</label>
                                            <input type="number" name="increment_amount" id="increment_amount_promotion" class="form-control" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document_promotion">Document:</label>
                                        <input type="file" name="document" id="document_promotion" class="form-control">
                                        <span id="current-document-link-promotion"></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-promotion-detail-btn">Save Promotion</button>
                            <button type="button" class="btn btn-danger" id="cancel-promotion-edit-btn">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Display Existing Promotion Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Promotion Date</th>
                        <th>New Designation</th>
                        <th>Old Designation</th>
                        <th>Pay Grade Change</th>
                        <th>New Salary</th>
                        <th>Old Grade</th>
                        <th>New Grade</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="promotion-details-table-body">
                    @if(isset($users) && $users->promotionDetails->count() > 0)
                        @foreach($users->promotionDetails as $promotionDetail)
                            <tr data-id="{{ $promotionDetail->id }}">
                                <td>{{ $promotionDetail->promotion_date }}</td>
                                <td>{{ $promotionDetail->new_designation }}</td>
                                <td>{{ $promotionDetail->old_designation }}</td>
                                <td>{{ $promotionDetail->pay_grade_change ? 'Yes' : 'No' }}</td>
                                <td>{{ $promotionDetail->new_salary }}</td>
                                <td>{{ $promotionDetail->oldGrade->grade ?? 'N/A' }}</td>
                                <td>{{ $promotionDetail->newGrade->grade ?? 'N/A' }}</td>
                                <td>
                                    @if($promotionDetail->document)
                                        <a href="{{ asset($promotionDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-promotion-detail" data-id="{{ $promotionDetail->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="{{ $promotionDetail->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No promotion details found.</td>
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
        const userId = "{{ $users->id }}";
        const promotionForm = $('#promotion-detail-form');
        const promotionAccordionCollapse = new bootstrap.Collapse($('#collapseFive'), { toggle: false });

        const newGradeSelectPromotion = document.getElementById('new_grade_id_promotion');
        const newSalaryInputPromotion = document.getElementById('new_salary_promotion');
        const newSalaryErrorPromotion = document.getElementById('new_salary_error_promotion');

        function validateNewSalaryPromotion() {
            const selectedOption = newGradeSelectPromotion.options[newGradeSelectPromotion.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                newSalaryInputPromotion.min = '';
                newSalaryInputPromotion.max = '';
                newSalaryErrorPromotion.textContent = '';
                return;
            }

            const min = parseFloat(selectedOption.dataset.min);
            const max = parseFloat(selectedOption.dataset.max);
            const newSalary = parseFloat(newSalaryInputPromotion.value);

            newSalaryInputPromotion.min = min;
            newSalaryInputPromotion.max = max;

            if (newSalary < min || newSalary > max) {
                newSalaryErrorPromotion.textContent = `New salary must be between ${min} and ${max}.`;
            } else {
                newSalaryErrorPromotion.textContent = '';
            }
        }

        newGradeSelectPromotion.addEventListener('change', validateNewSalaryPromotion);
        newSalaryInputPromotion.addEventListener('input', validateNewSalaryPromotion);

        const payGradeChangeCheckbox = document.getElementById('pay_grade_change');
        const payGradeChangeFields = document.getElementById('pay_grade_change_fields');

        payGradeChangeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                payGradeChangeFields.style.display = 'block';
            } else {
                payGradeChangeFields.style.display = 'none';
            }
        });

        const oldSalaryInputPromotion = document.getElementById('old_salaryy');
        const incrementAmountInputPromotion = document.getElementById('increment_amount_promotion');

        function calculateIncrementAmountPromotion() {
            const oldSalary = parseFloat(oldSalaryInputPromotion.value) || 0;
            const newSalary = parseFloat(newSalaryInputPromotion.value) || 0;
            const incrementAmount = newSalary - oldSalary;
            incrementAmountInputPromotion.value = incrementAmount.toFixed(2);
        }

        newSalaryInputPromotion.addEventListener('input', calculateIncrementAmountPromotion);

        function loadPromotionDetails() {
            $.ajax({
                url: `/promotionDetails/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.promotionDetail.length > 0) {
                        res.promotionDetail.forEach(promotion => {
                            tableData += `
                                <tr data-id="${promotion.id}">
                                    <td>${promotion.promotion_date}</td>
                                    <td>${promotion.new_designation}</td>
                                    <td>${promotion.old_designation}</td>
                                    <td>${promotion.pay_grade_change ? 'Yes' : 'No'}</td>
                                    <td>${promotion.new_salary}</td>
                                    <td>${promotion.old_grade ? promotion.old_grade.grade : 'N/A'}</td>
                                    <td>${promotion.new_grade ? promotion.new_grade.grade : 'N/A'}</td>
                                    <td>
                                        ${promotion.document 
                                            ? `<a href="${promotion.document}" target="_blank">View Document</a>` 
                                            : 'N/A'}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-promotion-detail" data-id="${promotion.id}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-promotion-detail" data-id="${promotion.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="7" class="text-center">No promotion details found.</td></tr>`;
                    }
                    $('#promotion-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    alert('Error loading promotion details: ' + xhr.responseText);
                }
            });
        }

        // Call loadPromotionDetails on page load
        loadPromotionDetails();

        $('button[data-bs-toggle="collapse"]').on('click', function() {
            const targetSelector = $(this).data('bs-target');
            const $collapseElement = $(targetSelector);
            const $form = $collapseElement.find('form');

            if ($form.length) {
                $form[0].reset();
                $('#promotion-detail-id').val('');
                $('#current-document-link').html('');
            }
        });

        // Show form for adding new promotion
        $('button[data-bs-target="#collapseFive"]').click(function() {
            promotionForm[0].reset();
            $('#promotion-detail-id').val('');
            $('#current-document-link').html('');
            $('#old_grade_id').val('{{ $users->salary_grade_id }}');
            promotionAccordionCollapse.show();
        });

        // Cancel button
        $('#cancel-promotion-edit-btn').click(function() {
            promotionAccordionCollapse.hide();
        });

        // ✅ Save Promotion (Add/Edit)
        promotionForm.submit(function(e) {
            e.preventDefault();
        const formData = new FormData(this);

            // 🔥 Force checkbox value
            formData.set('pay_grade_change', $('#pay_grade_change').is(':checked') ? 1 : 0);

            const promotionDetailId = $('#promotion-detail-id').val();
            const url = promotionDetailId ? `/promotionDetails/${promotionDetailId}` : '/promotionDetails';
            const method = 'POST';

            if (promotionDetailId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    promotionAccordionCollapse.hide();
                    loadPromotionDetails(); 
                },
                error: function(xhr) {
                    alert('Error saving promotion detail: ' + xhr.responseText);
                }
            });
        });

        // Edit Promotion
        $(document).on('click', '.edit-promotion-detail', function() {
        const promotionDetailId = $(this).data('id');
            $.ajax({
                url: `/promotionDetails/${promotionDetailId}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#promotion-detail-id').val(response.promotionDetail.id);
                    $('#promotion_date').val(response.promotionDetail.promotion_date);
                    $('#old_designation').val(response.promotionDetail.old_designation);
                    $('#new_designation').val(response.promotionDetail.new_designation);
                    $('#old_grade_id').val(response.promotionDetail.old_grade_id);
                    $('#new_grade_id').val(response.promotionDetail.new_grade_id);
                    $('#pay_grade_change').prop('checked', response.promotionDetail.pay_grade_change == 1);
                    $('#new_salary').val(response.promotionDetail.new_salary);

                    if (response.promotionDetail.document) {
                        $('#current-document-link-promotion').html(
                            `<a href="${response.promotionDetail.document}" target="_blank">View Current Document</a>`
                        );
                    } else {
                        $('#current-document-link-promotion').html('');
                    }
                    promotionAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching promotion detail: ' + xhr.responseText);
                }
            });
        });

        // Delete Promotion
        $(document).on('click', '.delete-promotion-detail', function() {
            if (confirm('Are you sure you want to delete this promotion detail?')) {
                const promotionDetailId = $(this).data('id');
                $.ajax({
                    url: `/promotionDetails/${promotionDetailId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        loadPromotionDetails(); 
                    },
                    error: function(xhr) {
                        alert('Error deleting promotion detail: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>

@endpush
