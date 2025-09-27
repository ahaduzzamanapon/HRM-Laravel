<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Child Allowance Details</h4>
            <button class="btn btn-primary btn-sm col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven"><i class="im im-icon-Add"></i> Add New</button>
        </div>

        <div class="accordion mb-4" id="childAllowanceAccordion">
            <div class="accordion-item">
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#childAllowanceAccordion">
                    <div class="accordion-body">
                        <form id="child-allowance-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="child-allowance-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="child_name">Child Name:</label>
                                        <input type="text" name="child_name" id="child_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="child_dob">Child Date of Birth:</label>
                                        <input type="date" name="child_dob" id="child_dob" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_age">Start Age:</label>
                                        <input type="number" name="start_age" id="start_age" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_month">Start Month:</label>
                                        <input type="month" name="start_month" id="start_month" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pay_year">Pay Year:</label>
                                        <input type="number" name="pay_year" id="pay_year" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_month">End Month:</label>
                                        <input type="month" name="end_month" id="end_month" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pay_amt">Pay Amount:</label>
                                        <input type="number" name="pay_amt" id="pay_amt" class="form-control" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-child-allowance-btn">Save Child Allowance</button>
                            <button type="button" class="btn btn-secondary" id="cancel-child-allowance-edit-btn" data-toggle="collapse" data-target="#collapseSeven">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Child Name</th>
                        <th>Date of Birth</th>
                        <th>Start Age</th>
                        <th>Start Month</th>
                        <th>Pay Year</th>
                        <th>End Month</th>
                        <th>Pay Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="child-allowances-table-body">
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const childAllowanceForm = $('#child-allowance-form');
        const childAllowanceAccordionCollapse = new bootstrap.Collapse($('#collapseSeven'), { toggle: false });
        const userId = "{{ $users->id }}";

        const childDobInput = document.getElementById('child_dob');
        const startAgeInput = document.getElementById('start_age');
        const startMonthInput = document.getElementById('start_month');
        const payYearInput = document.getElementById('pay_year');
        const endMonthInput = document.getElementById('end_month');

        function calculateStartMonth() {
            const childDob = new Date(childDobInput.value);
            const startAge = parseInt(startAgeInput.value) || 0;

            if (!isNaN(childDob.getTime())) {
                const startMonth = new Date(childDob.setFullYear(childDob.getFullYear() + startAge));
                startMonthInput.value = startMonth.toISOString().slice(0, 7);
            }
        }

        function calculateEndMonth() {
            const startMonth = new Date(startMonthInput.value);
            const payYear = parseInt(payYearInput.value) || 0;

            if (!isNaN(startMonth.getTime())) {
                const endMonth = new Date(startMonth.setFullYear(startMonth.getFullYear() + payYear));
                endMonthInput.value = endMonth.toISOString().slice(0, 7);
            }
        }

        childDobInput.addEventListener('input', calculateStartMonth);
        startAgeInput.addEventListener('input', calculateStartMonth);
        startMonthInput.addEventListener('input', calculateEndMonth);
        payYearInput.addEventListener('input', calculateEndMonth);

        function loadChildAllowances() {
            $.ajax({
                url: `/childAllowances/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.childAllowances.length > 0) {
                        res.childAllowances.forEach(allowance => {
                            tableData += `
                                <tr data-id="${allowance.id}">
                                    <td>${allowance.child_name}</td>
                                    <td>${allowance.child_dob}</td>
                                    <td>${allowance.start_age}</td>
                                    <td>${allowance.start_month}</td>
                                    <td>${allowance.pay_year}</td>
                                    <td>${allowance.end_month}</td>
                                    <td>${allowance.pay_amt}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info edit-child-allowance" data-id="${allowance.id}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger delete-child-allowance" data-id="${allowance.id}">Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        tableData = `<tr><td colspan="8" class="text-center">No child allowance details found.</td></tr>`;
                    }
                    $('#child-allowances-table-body').html(tableData);
                },
                error: function(xhr) {
                    alert('Error loading child allowances: ' + xhr.responseText);
                }
            });
        }

        loadChildAllowances();

        $('button[data-bs-target="#collapseSeven"]').click(function() {
            childAllowanceForm[0].reset();
            $('#child-allowance-id').val('');
            childAllowanceAccordionCollapse.show();
        });

        $('#cancel-child-allowance-edit-btn').click(function() {
            childAllowanceAccordionCollapse.hide();
        });

        childAllowanceForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const childAllowanceId = $('#child-allowance-id').val();
            const url = childAllowanceId ? `/childAllowances/${childAllowanceId}` : '/childAllowances';
            const method = 'POST';

            if (childAllowanceId) {
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
                    childAllowanceAccordionCollapse.hide();
                    loadChildAllowances(); 
                },
                error: function(xhr) {
                    alert('Error saving child allowance: ' + xhr.responseText);
                }
            });
        });

        $(document).on('click', '.edit-child-allowance', function() {
            const childAllowanceId = $(this).data('id');
            $.ajax({
                url: `/childAllowances/${childAllowanceId}/edit`,
                type: 'GET',
                success: function(response) {
                    $('#child-allowance-id').val(response.childAllowance.id);
                    $('#child_name').val(response.childAllowance.child_name);
                    $('#child_dob').val(response.childAllowance.child_dob);
                    $('#start_age').val(response.childAllowance.start_age);
                    $('#start_month').val(response.childAllowance.start_month);
                    $('#pay_year').val(response.childAllowance.pay_year);
                    $('#end_month').val(response.childAllowance.end_month);
                    $('#pay_amt').val(response.childAllowance.pay_amt);
                    childAllowanceAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching child allowance: ' + xhr.responseText);
                }
            });
        });

        $(document).on('click', '.delete-child-allowance', function() {
            if (confirm('Are you sure you want to delete this child allowance?')) {
                const childAllowanceId = $(this).data('id');
                $.ajax({
                    url: `/childAllowances/${childAllowanceId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        loadChildAllowances();
                    },
                    error: function(xhr) {
                        alert('Error deleting child allowance: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endpush
