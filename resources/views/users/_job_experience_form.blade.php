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
                                <textarea name="description" id="descriptionn" class="form-control" rows="3"></textarea>
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
        const userId = "{{ $users->id }}";
        const jobExperienceForm = $('#job-experience-form');
        const jobExperienceAccordionCollapse = new bootstrap.Collapse($('#collapseTwo'), { toggle: false });

        // -------------------------
        // Load job experiences
        // -------------------------
        function loadJobExperience() {
            $.ajax({
                url: `/jobExperiences/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {

                    // console.log(res);

                    let tableData = '';

                    if ( res.jobExperience.length > 0) {
                        res.jobExperience.forEach(job => {
                            tableData += `<tr data-id="${job.id}">
                                <td>${job.company_name}</td>
                                <td>${job.job_title}</td>
                                <td>${job.start_date}</td>
                                <td>${job.end_date}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-job-experience" data-id="${job.id}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-job-experience" data-id="${job.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="5" class="text-center">No job experience details found.</td></tr>';
                    }

                    $('#job-experience-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading job experience:', xhr.responseText);
                }
            });
        }

        // Initial load
        loadJobExperience();

        // -------------------------
        // Reset form on accordion toggle
        // -------------------------
        $('button[data-bs-toggle="collapse"]').on('click', function() {
            const targetSelector = $(this).data('bs-target');
            const $collapseElement = $(targetSelector);
            const $form = $collapseElement.find('form');

            if ($form.length) {
                $form[0].reset();
                $('#job-experience-id').val('');
            }
        });

        // -------------------------
        // Cancel button
        // -------------------------
        $('#cancel-job-experience-edit-btn').click(function() {
            jobExperienceForm[0].reset();
            $('#job-experience-id').val('');
            jobExperienceAccordionCollapse.hide();
        });

        // -------------------------
        // Save Job Experience (Add/Edit)
        // -------------------------
        jobExperienceForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const jobExperienceId = $('#job-experience-id').val();
            const url = jobExperienceId ? `/jobExperiences/${jobExperienceId}` : '/jobExperiences';
            const method = 'POST';

            if (jobExperienceId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadJobExperience();
                    if (response.error) {
                        alert('Error: ' + response.message);
                        return;
                    }

                    alert(response.message);
                    jobExperienceForm[0].reset();
                    $('#job-experience-id').val('');
                    jobExperienceAccordionCollapse.hide();
                },
                error: function(xhr) {
                    alert('Error saving job experience: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Edit Job Experience
        // -------------------------
        $(document).on('click', '.edit-job-experience', function() {
            const jobExperienceId = $(this).data('id');

            $.ajax({
                url: `/jobExperiences/${jobExperienceId}/edit`,
                type: 'GET',
                success: function(response) {
                    loadJobExperience();

                    const job = response.jobExperience;
                    $('#job-experience-id').val(job.id);
                    $('#company_name').val(job.company_name);
                    $('#job_title').val(job.job_title);
                    $('#start_datee').val(job.start_date);
                    $('#end_datee').val(job.end_date);
                    $('#descriptionn').val(job.description);
                    jobExperienceAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching job experience: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Delete Job Experience
        // -------------------------
        $(document).on('click', '.delete-job-experience', function() {
            const jobExperienceId = $(this).data('id');

            if (!confirm('Are you sure you want to delete this job experience?')) return;

            $.ajax({
                url: `/jobExperiences/${jobExperienceId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    loadJobExperience();

                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error deleting job experience: ' + xhr.responseText);
                }
            });
        });
    });
</script>




@endpush
