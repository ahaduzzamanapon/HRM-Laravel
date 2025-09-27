<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Salary Increment Details</h4>
            <button class="btn btn-primary btn-sm  col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix"><i class="im im-icon-Add"></i> Add New</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="salaryIncrementsAccordion">
            <div class="accordion-item">
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#salaryIncrementsAccordion">
                    <div class="accordion-body">
                        <form id="salary-increment-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="salary-increment-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="increment_date">Increment Date:</label>
                                        <input type="date" name="increment_date" id="increment_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_salary">Old Salary:</label>
                                        <input type="number" name="old_salary" id="old_salaryy" class="form-control" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_grade_id">Old Grade:</label>
                                        <select name="old_grade_id" id="old_grade_id" class="form-control">
                                            <option value="">Select Old Grade</option>
                                            @foreach($salaryGrades as $grade)
                                                <option value="{{ $grade->id }}" {{ $users->salary_grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_salary">New Salary:</label>
                                        <input type="number" name="new_salary" id="new_salaryy" class="form-control" step="0.01">
                                        <small id="new_salary_error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_grade_id">New Grade:</label>
                                        <select name="new_grade_id" id="new_grade_id" class="form-control">
                                            <option value="">Select New Grade</option>
                                            @foreach($salaryGrades as $grade)
                                                <option value="{{ $grade->id }}" data-min="{{ $grade->starting_salary }}" data-max="{{ $grade->end_salary }}">{{ $grade->grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="increment_amount">Increment Amount:</label>
                                        <input type="number" name="increment_amount" id="increment_amount" class="form-control" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="document_increment">Document:</label>
                                <input type="file" name="document" id="document_increment" class="form-control">
                                <span id="current-document-link-increment"></span>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-salary-increment-btn">Save Increment</button>
                            <button type="button" class="btn btn-secondary" id="cancel-salary-increment-edit-btn" data-toggle="collapse" data-target="#collapseSix">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Salary Increment Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Increment Date</th>
                        <th>Old Salary</th>
                        <th>New Salary</th>
                        <th>Increment Amount</th>
                        <th>Old Grade</th>
                        <th>New Grade</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="salary-increments-table-body">
                    @if(isset($users) && $users->salaryIncrements->count() > 0)
                        @foreach($users->salaryIncrements as $salaryIncrement)
                            <tr data-id="{{ $salaryIncrement->id }}">
                                <td>{{ $salaryIncrement->increment_date }}</td>
                                <td>{{ $salaryIncrement->old_salary }}</td>
                                <td>{{ $salaryIncrement->new_salary }}</td>
                                <td>{{ $salaryIncrement->increment_amount }}</td>
                                <td>{{ $salaryIncrement->oldGrade->grade ?? 'N/A' }}</td>
                                <td>{{ $salaryIncrement->newGrade->grade ?? 'N/A' }}</td>
                                <td>
                                    @if($salaryIncrement->document)
                                        <a href="{{ asset($salaryIncrement->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-salary-increment" data-id="{{ $salaryIncrement->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-salary-increment" data-id="{{ $salaryIncrement->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No salary increment details found.</td>
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
        const salaryIncrementForm = $('#salary-increment-form');
        const salaryIncrementsAccordionCollapse = new bootstrap.Collapse($('#collapseSix'), { toggle: false });
        const userId = "{{ $users->id }}"; // Laravel Blade user id

        const newGradeSelect = document.getElementById('new_grade_id');
        const newSalaryInput = document.getElementById('new_salaryy');
        const newSalaryError = document.getElementById('new_salary_error');

        function validateNewSalary() {
            const selectedOption = newGradeSelect.options[newGradeSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                newSalaryInput.min = '';
                newSalaryInput.max = '';
                newSalaryError.textContent = '';
                return;
            }

            const min = parseFloat(selectedOption.dataset.min);
            const max = parseFloat(selectedOption.dataset.max);
            const newSalary = parseFloat(newSalaryInput.value);

            newSalaryInput.min = min;
            newSalaryInput.max = max;

            if (newSalary < min || newSalary > max) {
                newSalaryError.textContent = `New salary must be between ${min} and ${max}.`;
            } else {
                newSalaryError.textContent = '';
            }
        }

        newGradeSelect.addEventListener('change', validateNewSalary);
        newSalaryInput.addEventListener('input', validateNewSalary);

        const oldSalaryInput = document.getElementById('old_salaryy');
        const incrementAmountInput = document.getElementById('increment_amount');

        function calculateIncrementAmount() {
            const oldSalary = parseFloat(oldSalaryInput.value) || 0;
            const newSalary = parseFloat(newSalaryInput.value) || 0;
            const incrementAmount = newSalary - oldSalary;
            incrementAmountInput.value = incrementAmount.toFixed(2);
        }

        oldSalaryInput.addEventListener('input', calculateIncrementAmount);
        newSalaryInput.addEventListener('input', calculateIncrementAmount);

        function loadSalaryIncrements() {
            $.ajax({
                url: `/salaryIncrements/list/${userId}`, // Laravel route for increments list
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.salaryIncrements.length > 0) {
                        res.salaryIncrements.forEach(inc => {
                            tableData += `
                                <tr data-id="${inc.id}">
                                    <td>${inc.increment_date}</td>
                                    <td>${inc.old_salary}</td>
                                    <td>${inc.new_salary}</td>
                                    <td>${inc.increment_amount}</td>
                                    <td>${inc.old_grade ? inc.old_grade.grade : 'N/A'}</td>
                                    <td>${inc.new_grade ? inc.new_grade.grade : 'N/A'}</td>
                                    <td>
                                        ${inc.document 
                                            ? `<a href="{{asset('/')}}${inc.document}" target="_blank">View Document</a>` 
                                            : ''}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-salary-increment" data-id="${inc.id}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-salary-increment" data-id="${inc.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="6" class="text-center">No salary increment details found.</td></tr>`;
                    }
                    $('#salary-increments-table-body').html(tableData);
                },
                error: function(xhr) {
                    alert('Error loading salary increments: ' + xhr.responseText);
                }
            });
        }

        // Call loadSalaryIncrements on page load
        loadSalaryIncrements();

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

        // Show form for adding new salary increment
        $('button[data-bs-target="#collapseSix"]').click(function() {
            salaryIncrementForm[0].reset();
            $('#salary-increment-id').val('');
            $('#current-document-link').html('');
            $('#old_salaryy').val('{{ $users->basic_salary }}');
            $('#old_grade_id').val('{{ $users->salary_grade_id }}');
            salaryIncrementsAccordionCollapse.show();
        });

        // Cancel button
        $('#cancel-salary-increment-edit-btn').click(function() {
            salaryIncrementsAccordionCollapse.hide();
        });

        // Save Salary Increment (Add/Edit)
        salaryIncrementForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const salaryIncrementId = $('#salary-increment-id').val();
            const url = salaryIncrementId ? `/salaryIncrements/${salaryIncrementId}` : '/salaryIncrements';
            const method = 'POST';

            if (salaryIncrementId) {
                formData.append('_method', 'PATCH'); // Spoof PATCH
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    salaryIncrementsAccordionCollapse.hide();
                    loadSalaryIncrements(); 
                },
                error: function(xhr) {
                    alert('Error saving salary increment: ' + xhr.responseText);
                }
            });
        });

        // Edit Salary Increment
        $(document).on('click', '.edit-salary-increment', function() {
            const salaryIncrementId = $(this).data('id');
            $.ajax({
                url: `/salaryIncrements/${salaryIncrementId}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#salary-increment-id').val(response.salaryIncrements.id);
                    $('#increment_date').val(response.salaryIncrements.increment_date);
                    $('#old_salaryy').val(response.salaryIncrements.old_salary);
                    $('#new_salaryy').val(response.salaryIncrements.new_salary);
                    $('#increment_amount').val(response.salaryIncrements.increment_amount);
                    $('#old_grade_id').val(response.salaryIncrements.old_grade_id);
                    $('#new_grade_id').val(response.salaryIncrements.new_grade_id);

                    if (response.salaryIncrements.document) {
                        $('#current-document-link-increment').html(`<a href="${response.salaryIncrements.document}" target="_blank">View Current Document</a>`);
                    } else {
                        $('#current-document-link-increment').html('');
                    }
                    salaryIncrementsAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching salary increment: ' + xhr.responseText);
                }
            });
        });

        // Delete Salary Increment
        $(document).on('click', '.delete-salary-increment', function() {
            if (confirm('Are you sure you want to delete this salary increment?')) {
                const salaryIncrementId = $(this).data('id');
                $.ajax({
                    url: `/salaryIncrements/${salaryIncrementId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        loadSalaryIncrements();
                    },
                    error: function(xhr) {
                        alert('Error deleting salary increment: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>

@endpush
