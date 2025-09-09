<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4>Job Experience Details</h4>
            <button class="btn btn-primary btn-sm  col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="im im-icon-Add"></i> Add New</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="jobExperienceAccordion">
            <div class="accordion-item">
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#jobExperienceAccordion">
                    <div class="accordion-body">
                        <form id="job-experience-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="job-experience-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name:</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="job_title">Job Title:</label>
                                        <input type="text" name="job_title" id="job_title" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_datee">Start Date:</label>
                                        <input type="date" name="start_date" id="start_datee" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_datee">End Date:</label>
                                        <input type="date" name="end_date" id="end_datee" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descriptionn">Description:</label>
                                <textarea name="descriptionn" id="descriptionn" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-job-experience-btn">Save Job Experience</button>
                            <button type="button" class="btn btn-secondary" id="cancel-job-experience-edit-btn" data-toggle="collapse" data-target="#collapseTwo">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Job Experience Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Job Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="job-experience-table-body">
                    @if(isset($users) && $users->jobExperiences->count() > 0)
                        @foreach($users->jobExperiences as $jobExperience)
                            <tr data-id="{{ $jobExperience->id }}">
                                <td>{{ $jobExperience->company_name }}</td>
                                <td>{{ $jobExperience->job_title }}</td>
                                <td>{{ $jobExperience->start_date }}</td>
                                <td>{{ $jobExperience->end_date }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-job-experience" data-id="{{ $jobExperience->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-job-experience" data-id="{{ $jobExperience->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No job experience details found.</td>
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
            const jobExperienceForm = $('#job-experience-form');
            const jobExperienceAccordionCollapse = new bootstrap.Collapse($('#collapseTwo'), { toggle: false });

            // Show form for adding new job experience
            $('#add-new-job-experience-btn').click(function() {
                jobExperienceForm[0].reset(); // Clear form
                $('#job-experience-id').val(''); // Clear ID for new entry
                jobExperienceAccordionCollapse.show(); // Show accordion
            });

            // Cancel button for form
            $('#cancel-job-experience-edit-btn').click(function() {
                jobExperienceAccordionCollapse.hide(); // Hide accordion
            });

            // Save Job Experience (Add/Edit)
            jobExperienceForm.submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const jobExperienceId = $('#job-experience-id').val();
                const url = jobExperienceId ? `/jobExperiences/${jobExperienceId}` : '/jobExperiences';
                const method = jobExperienceId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

                if (jobExperienceId) {
                    formData.append('_method', 'PATCH'); // Spoof PATCH method for Laravel
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            alert(response.message);
                            jobExperienceAccordionCollapse.hide();
                            location.reload();
                        } else {
                            alert('Failed to save job experience.');
                        }
                   // For simplicity, reload page. In production, update table dynamically.
                    },
                    error: function(xhr) {
                        alert('Error saving job experience: ' + xhr.responseText);
                    }
                });
            });

            // Edit Job Experience
            $(document).on('click', '.edit-job-experience', function() {
                const jobExperienceId = $(this).data('id');
                $.ajax({
                    url: `/jobExperiences/${jobExperienceId}/edit`, // Laravel's edit route returns data for form
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        
                        $('#job-experience-id').val(response.jobExperience.id);
                        $('#company_name').val(response.jobExperience.company_name);
                        $('#job_title').val(response.jobExperience.job_title);
                        $('#start_datee').val(response.jobExperience.start_date);
                        $('#end_datee').val(response.jobExperience.end_date);
                        $('#descriptionn').val(response.jobExperience.description);
                        jobExperienceAccordionCollapse.show(); // Show accordion
                    },
                    error: function(xhr) {
                        alert('Error fetching job experience: ' + xhr.responseText);
                    }
                });
            });

            // Delete Job Experience
            $(document).on('click', '.delete-job-experience', function() {
                if (confirm('Are you sure you want to delete this job experience?')) {
                    const jobExperienceId = $(this).data('id');
                    $.ajax({
                        url: `/jobExperiences/${jobExperienceId}`,
                        type: 'POST', // Laravel uses POST for DELETE with _method field
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                            $(`tr[data-id="${jobExperienceId}"]`).remove();
                            if ($('#job-experience-table-body tr').length === 0) {
                                $('#job-experience-table-body').html('<tr><td colspan="5" class="text-center">No job experience details found.</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting job experience: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
