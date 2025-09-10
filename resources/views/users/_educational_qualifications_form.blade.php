<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4 class="col-md-10">Educational Qualifications</h4>
            <button class="btn btn-primary btn-sm col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="im
im-icon-Add"></i> Add New</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="educationalQualificationsAccordion">
            <div class="accordion-item">

                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#educationalQualificationsAccordion">
                    <div class="accordion-body">
                        <form id="educational-qualification-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="educational-qualification-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="degree">Degree:</label>
                                        <input type="text" name="degree" id="degree" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="institution">Institution:</label>
                                        <input type="text" name="institution" id="institution" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="passing_year">Passing Year:</label>
                                        <input type="text" name="passing_year" id="passing_year" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="grade">Grade:</label>
                                        <input type="text" name="grade" id="grade" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="document">Document:</label>
                                <input type="file" name="document" id="document" class="form-control">
                                <span id="current-document-link"></span>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-educational-qualification-btn">Save Qualification</button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEducationalQualificationEdit()" data-toggle="collapse" data-target="#collapseThree">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Educational Qualifications in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Passing Year</th>
                        <th>Grade</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="educational-qualifications-table-body">
                    @if(isset($users) && $users->educationalQualifications->count() > 0)
                        @foreach($users->educationalQualifications as $educationalQualification)
                            <tr data-id="{{ $educationalQualification->id }}">
                                <td>{{ $educationalQualification->degree }}</td>
                                <td>{{ $educationalQualification->institution }}</td>
                                <td>{{ $educationalQualification->passing_year }}</td>
                                <td>{{ $educationalQualification->grade }}</td>
                                <td>
                                    @if($educationalQualification->document)
                                        <a href="{{ asset($educationalQualification->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" id="edit-educational-qualification" data-id="{{ $educationalQualification->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-educational-qualification" data-id="{{ $educationalQualification->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No educational qualifications found.</td>
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
        const educationalQualificationForm = $('#educational-qualification-form');
        const educationalQualificationsAccordionCollapse = new bootstrap.Collapse($('#collapseThree'), { toggle: false });

        // -------------------------
        // Load educational qualifications
        // -------------------------
        function loadEducationalQualifications() {
            $.ajax({
                url: `/educationalQualifications/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';

                    if (res.educationalQualification.length > 0) {
                        res.educationalQualification.forEach(edu => {
                            tableData += `<tr data-id="${edu.id}">
                                <td>${edu.degree}</td>
                                <td>${edu.institution}</td>
                                <td>${edu.passing_year}</td>
                                <td>${edu.grade}</td>
                                <td>${edu.document ? `<a href="${edu.document}" target="_blank">View</a>` : 'N/A'}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-educational-qualification" data-id="${edu.id}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-educational-qualification" data-id="${edu.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="6" class="text-center">No educational qualifications found.</td></tr>';
                    }

                    $('#educational-qualifications-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading educational qualifications:', xhr.responseText);
                }
            });
        }

        // Initial load
        loadEducationalQualifications();

        // -------------------------
        // Reset form on accordion toggle
        // -------------------------
        $('button[data-bs-toggle="collapse"]').on('click', function() {
            const targetSelector = $(this).data('bs-target');
            const $collapseElement = $(targetSelector);
            const $form = $collapseElement.find('form');

            if ($form.length) {
                $form[0].reset();
                $('#educational-qualification-id').val('');
                $('#current-document-link').html('');
            }
        });

        // -------------------------
        // Cancel button
        // -------------------------
        $('#cancel-educational-qualification-edit-btn').click(function() {
            educationalQualificationForm[0].reset();
            $('#educational-qualification-id').val('');
            $('#current-document-link').html('');
            educationalQualificationsAccordionCollapse.hide();
        });

        // -------------------------
        // Save Educational Qualification (Add/Edit)
        // -------------------------
        educationalQualificationForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const eduId = $('#educational-qualification-id').val();
            const url = eduId ? `/educationalQualifications/${eduId}` : '/educationalQualifications';
            const method = 'POST';

            if (eduId) formData.append('_method', 'PATCH');

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadEducationalQualifications();
                    if(response.error){
                        alert(response.message);
                        return false;
                    }
                    alert(response.message);
                    educationalQualificationForm[0].reset();
                    $('#educational-qualification-id').val('');
                    $('#current-document-link').html('');
                    educationalQualificationsAccordionCollapse.hide();
                },
                error: function(xhr) {
                    alert('Error saving educational qualification: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Edit Educational Qualification
        // -------------------------
        $(document).on('click', '.edit-educational-qualification', function() {
            const eduId = $(this).data('id');

            $.ajax({
                url: `/educationalQualifications/${eduId}/edit`,
                type: 'GET',
                success: function(response) {
                    const edu = response.educationalQualification;
                    $('#educational-qualification-id').val(edu.id);
                    $('#degree').val(edu.degree);
                    $('#institution').val(edu.institution);
                    $('#passing_year').val(edu.passing_year);
                    $('#grade').val(edu.grade);

                    if (edu.document) {
                        $('#current-document-link').html(`<a href="${edu.document}" target="_blank">View Current Document</a>`);
                    } else {
                        $('#current-document-link').html('');
                    }

                    educationalQualificationsAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching educational qualification: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Delete Educational Qualification
        // -------------------------
        $(document).on('click', '.delete-educational-qualification', function() {
            const eduId = $(this).data('id');
            if (!confirm('Are you sure you want to delete this educational qualification?')) return;

            $.ajax({
                url: `/educationalQualifications/${eduId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    loadEducationalQualifications();
                },
                error: function(xhr) {
                    alert('Error deleting educational qualification: ' + xhr.responseText);
                }
            });
        });

    });
</script>

@endpush
