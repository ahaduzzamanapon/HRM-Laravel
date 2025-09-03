<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Salary Increment Details</h4>
            <button type="button" class="btn btn-primary btn-sm" id="add-new-salary-increment-btn" data-toggle="collapse" data-target="#collapseOne">Add New Increment</button>
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
                                        <input type="date" name="increment_date" id="increment_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_salary">Old Salary:</label>
                                        <input type="number" name="old_salary" id="old_salary" class="form-control" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_salary">New Salary:</label>
                                        <input type="number" name="new_salary" id="new_salary" class="form-control" step="0.01">
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
                                <label for="document">Document:</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <span id="current-document-link"></span>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-salary-increment-btn">Save Increment</button>
                            <div class="row">
    <div class="col-md-12">
        
        <div class="d-flex justify-content-between align-items-center">
            <h4>Salary Increment Details</h4>
            <button type="button" class="btn btn-primary btn-sm" id="add-new-salary-increment-btn" data-toggle="collapse" data-target="#collapseSix">Add New Increment</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="salaryIncrementsAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Salary Increment Form
                    </button>
                </h2>
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
                                        <input type="date" name="increment_date" id="increment_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="old_salary">Old Salary:</label>
                                        <input type="number" name="old_salary" id="old_salary" class="form-control" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="new_salary">New Salary:</label>
                                        <input type="number" name="new_salary" id="new_salary" class="form-control" step="0.01">
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
                                <label for="document">Document:</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <span id="current-document-link"></span>
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

@section('footer_scripts')
@parent
<script>
    $(document).ready(function() {
        const salaryIncrementForm = $('#salary-increment-form');
        const salaryIncrementsAccordionCollapse = new bootstrap.Collapse($('#collapseSix'), { toggle: false });

        // Show form for adding new salary increment
        $('#add-new-salary-increment-btn').click(function() {
            salaryIncrementForm[0].reset(); // Clear form
            $('#salary-increment-id').val(''); // Clear ID for new entry
            $('#current-document-link').html(''); // Clear document link
            salaryIncrementsAccordionCollapse.show(); // Show accordion
        });

        // Cancel button for form
        $('#cancel-salary-increment-edit-btn').click(function() {
            salaryIncrementsAccordionCollapse.hide(); // Hide accordion
        });

        // Save Salary Increment (Add/Edit)
        salaryIncrementForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const salaryIncrementId = $('#salary-increment-id').val();
            const url = salaryIncrementId ? `/salaryIncrements/${salaryIncrementId}` : '/salaryIncrements';
            const method = salaryIncrementId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

            if (salaryIncrementId) {
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
                    salaryIncrementsAccordionCollapse.hide();
                    location.reload(); // For simplicity, reload page. In production, update table dynamically.
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
                url: `/salaryIncrements/${salaryIncrementId}/edit`, // Laravel's edit route returns data for form
                type: 'GET',
                success: function(response) {
                    $('#salary-increment-id').val(response.id);
                    $('#increment_date').val(response.increment_date);
                    $('#old_salary').val(response.old_salary);
                    $('#new_salary').val(response.new_salary);
                    $('#increment_amount').val(response.increment_amount);
                    if (response.document) {
                        $('#current-document-link').html(`<a href="${response.document}" target="_blank">View Current Document</a>`);
                    } else {
                        $('#current-document-link').html('');
                    }
                    salaryIncrementsAccordionCollapse.show(); // Show accordion
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
                    type: 'POST', // Laravel uses POST for DELETE with _method field
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                        $(`tr[data-id="${salaryIncrementId}"]`).remove();
                        if ($('#salary-increments-table-body tr').length === 0) {
                            $('#salary-increments-table-body').html('<tr><td colspan="6" class="text-center">No salary increment details found.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        alert('Error deleting salary increment: ' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endsection