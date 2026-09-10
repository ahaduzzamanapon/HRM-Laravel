<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-primary"><i class="im im-icon-Engineering me-2"></i>Job Experience Details</h4>
            <button class="btn btn-primary btn-sm px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="im im-icon-Add me-1"></i> Add New
            </button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="jobExperienceAccordion">
            <div class="accordion-item border shadow-sm rounded">
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#jobExperienceAccordion">
                    <div class="accordion-body bg-light p-4">
                        <h5 class="fw-bold mb-3 text-dark" id="job-form-title">Add / Edit Job Experience</h5>
                        <form id="job-experience-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="job-experience-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name" class="fw-bold mb-1">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" id="company_name" class="form-control" required placeholder="e.g. Acme Corporation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="job_title" class="fw-bold mb-1">Job Title / Designation <span class="text-danger">*</span></label>
                                        <input type="text" name="job_title" id="job_title" class="form-control" required placeholder="e.g. Senior Software Engineer">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_datee" class="fw-bold mb-1">Start Date</label>
                                        <input type="date" name="start_date" id="start_datee" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_datee" class="fw-bold mb-1">End Date</label>
                                        <input type="date" name="end_date" id="end_datee" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="descriptionn" class="fw-bold mb-1">Job Description / Responsibilities:</label>
                                <textarea name="description" id="descriptionn" class="form-control" rows="3" placeholder="Summary of key accomplishments..."></textarea>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancel-job-btn">Cancel</button>
                                <button type="submit" class="btn btn-success px-4" id="save-job-experience-btn">
                                    <i class="im im-icon-Save me-1"></i> Save Job Experience
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Job Experience Details in a Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Company Name</th>
                        <th>Job Title</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-end pe-3" style="width: 220px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="job-experience-table-body">
                    @if(isset($users) && $users->jobExperiences->count() > 0)
                        @foreach($users->jobExperiences as $jobExperience)
                            <tr data-id="{{ $jobExperience->id }}">
                                <td class="fw-bold text-dark">{{ $jobExperience->company_name }}</td>
                                <td>{{ $jobExperience->job_title }}</td>
                                <td>{{ $jobExperience->start_date ?? 'N/A' }}</td>
                                <td>{{ $jobExperience->end_date ?? 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-job-experience" data-id="{{ $jobExperience->id }}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-job-experience" data-id="{{ $jobExperience->id }}" title="Edit Job Experience">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-job-experience" data-id="{{ $jobExperience->id }}" title="Delete Job Experience">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No job experience details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Job Experience Modal -->
<div class="modal fade" id="viewJobExperienceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold text-white"><i class="im im-icon-Engineering me-2"></i>Job Experience Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th class="bg-light" style="width: 35%;">Company Name:</th>
                            <td class="fw-bold text-primary" id="view_job_company">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Job Title:</th>
                            <td id="view_job_title">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Start Date:</th>
                            <td id="view_job_start">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">End Date:</th>
                            <td id="view_job_end">Loading...</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Description:</th>
                            <td id="view_job_desc">Loading...</td>
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
        const jobExperienceForm = $('#job-experience-form');

        function toggleJobCollapse(show) {
            if (show) {
                $('#collapseTwo').collapse('show');
            } else {
                $('#collapseTwo').collapse('hide');
            }
        }

        function loadJobExperience() {
            $.ajax({
                url: `/jobExperiences/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.jobExperience && res.jobExperience.length > 0) {
                        res.jobExperience.forEach(job => {
                            tableData += `<tr data-id="${job.id}">
                                <td class="fw-bold text-dark">${job.company_name || 'N/A'}</td>
                                <td>${job.job_title || 'N/A'}</td>
                                <td>${job.start_date || 'N/A'}</td>
                                <td>${job.end_date || 'N/A'}</td>
                                <td class="text-end pe-3">
                                    <button type="button" class="btn btn-sm btn-info text-white me-1 view-job-experience" data-id="${job.id}" title="View Details">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning text-dark me-1 edit-job-experience" data-id="${job.id}" title="Edit Job Experience">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-job-experience" data-id="${job.id}" title="Delete Job Experience">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="5" class="text-center py-4 text-muted">No job experience details found.</td></tr>';
                    }
                    $('#job-experience-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading job experience table:', xhr.responseText);
                }
            });
        }

        loadJobExperience();

        $('#cancel-job-btn').click(function() {
            jobExperienceForm[0].reset();
            $('#job-experience-id').val('');
            $('#job-form-title').text('Add / Edit Job Experience');
            toggleJobCollapse(false);
        });

        jobExperienceForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const jId = $('#job-experience-id').val();
            const url = jId ? `/jobExperiences/${jId}` : '/jobExperiences';

            if (jId) {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadJobExperience();
                    if(response.error){
                        alert(response.message || 'Error saving job experience');
                        return;
                    }
                    alert(response.message || 'Job experience saved successfully');
                    jobExperienceForm[0].reset();
                    $('#job-experience-id').val('');
                    $('#job-form-title').text('Add / Edit Job Experience');
                    toggleJobCollapse(false);
                },
                error: function(xhr) {
                    alert('Error saving job experience: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // View Job Experience Modal
        $(document).on('click', '.view-job-experience', function() {
            const jId = $(this).data('id');
            $('#view_job_company').text('Loading...');
            $('#view_job_title').text('Loading...');
            $('#view_job_start').text('Loading...');
            $('#view_job_end').text('Loading...');
            $('#view_job_desc').text('Loading...');

            $('#viewJobExperienceModal').modal('show');

            $.ajax({
                url: `/jobExperiences/${jId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const job = response.jobExperience;
                    if (job) {
                        $('#view_job_company').text(job.company_name || 'N/A');
                        $('#view_job_title').text(job.job_title || 'N/A');
                        $('#view_job_start').text(job.start_date || 'N/A');
                        $('#view_job_end').text(job.end_date || 'N/A');
                        $('#view_job_desc').text(job.description || 'No description provided');
                    }
                },
                error: function(xhr) {
                    $('#view_job_company').text('Error loading details');
                }
            });
        });

        // Edit Job Experience
        $(document).on('click', '.edit-job-experience', function() {
            const jId = $(this).data('id');
            $.ajax({
                url: `/jobExperiences/${jId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const job = response.jobExperience;
                    if (job) {
                        $('#job-experience-id').val(job.id);
                        $('#company_name').val(job.company_name);
                        $('#job_title').val(job.job_title);
                        $('#start_datee').val(job.start_date);
                        $('#end_datee').val(job.end_date);
                        $('#descriptionn').val(job.description);

                        $('#job-form-title').text('Edit Job Experience #' + job.id);
                        toggleJobCollapse(true);

                        $('html, body').animate({
                            scrollTop: $('#jobExperienceAccordion').offset().top - 100
                        }, 300);
                    }
                },
                error: function(xhr) {
                    alert('Error fetching job experience: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                }
            });
        });

        // Delete Job Experience
        $(document).on('click', '.delete-job-experience', function() {
            const jId = $(this).data('id');
            if (confirm('Are you sure you want to delete this job experience?')) {
                $.ajax({
                    url: `/jobExperiences/${jId}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message || 'Deleted successfully.');
                        loadJobExperience();
                    },
                    error: function(xhr) {
                        alert('Error deleting job experience: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
                    }
                });
            }
        });
    });
</script>
@endpush
