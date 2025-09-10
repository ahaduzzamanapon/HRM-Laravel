<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4>Nominee Information</h4>
            <button class="btn btn-primary btn-sm  col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="im im-icon-Add"></i> Add New</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="nomineeInformationAccordion">
            <div class="accordion-item">
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#nomineeInformationAccordion">
                    <div class="accordion-body">
                        <form id="nominee-information-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="nominee-information-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nominee_name">Nominee Name:</label>
                                        <input type="text" name="nominee_name" id="nominee_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="relation">Relation:</label>
                                        <input type="text" name="relation" id="relation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="voter_id">Voter ID:</label>
                                        <input type="text" name="voter_id" id="voter_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Photo:</label>
                                        <input type="file" name="photo" id="photo" class="form-control">
                                        <span id="current-photo-link"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="percentage">Percentage:</label>
                                <input type="number" name="percentage" id="percentage" class="form-control" step="0.01">
                            </div>

                            <button type="submit" class="btn btn-success" id="save-nominee-information-btn">Save Nominee</button>
                            <button type="button" class="btn btn-secondary" id="cancel-nominee-information-edit-btn" data-toggle="collapse" data-target="#collapseFour">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Nominee Information in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nominee Name</th>
                        <th>Relation</th>
                        <th>Voter ID</th>
                        <th>Photo</th>
                        <th>Percentage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="nominee-information-table-body">
                    @if(isset($users) && $users->nomineeInformation->count() > 0)
                        @foreach($users->nomineeInformation as $nomineeInformation)
                            <tr data-id="{{ $nomineeInformation->id }}">
                                <td>{{ $nomineeInformation->nominee_name }}</td>
                                <td>{{ $nomineeInformation->relation }}</td>
                                <td>{{ $nomineeInformation->voter_id }}</td>
                                <td>
                                    @if($nomineeInformation->photo)
                                        <a href="{{ asset($nomineeInformation->photo) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $nomineeInformation->percentage }}%</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-nominee-information" data-id="{{ $nomineeInformation->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-nominee-information" data-id="{{ $nomineeInformation->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center">No nominee information found.</td>
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
        const nomineeInformationForm = $('#nominee-information-form');
        const nomineeInformationAccordionCollapse = new bootstrap.Collapse($('#collapseFour'), { toggle: false });

        // -------------------------
        // Load nominee information
        // -------------------------
        function loadNomineeInformation() {
            $.ajax({
                url: `/nomineeInformation/list/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';

                    if (res.nomineeInformation.length > 0) {
                        res.nomineeInformation.forEach(nominee => {
                            tableData += `<tr data-id="${nominee.id}">
                                <td>${nominee.nominee_name}</td>
                                <td>${nominee.relation}</td>
                                <td>${nominee.voter_id}</td>
                                <td>${nominee.photo ? `<a href="{{asset('/')}}${nominee.photo}" target="_blank">View</a>` : 'N/A'}</td>
                                <td>${nominee.percentage}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-nominee-information" data-id="${nominee.id}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-nominee-information" data-id="${nominee.id}">Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        tableData = '<tr><td colspan="6" class="text-center">No nominee information found.</td></tr>';
                    }

                    $('#nominee-information-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading nominee information:', xhr.responseText);
                }
            });
        }

        // Initial load
        loadNomineeInformation();

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
        // Show form for adding new nominee information
        // -------------------------
        $('#add-new-nominee-information-btn').click(function() {
            nomineeInformationForm[0].reset();
            $('#nominee-information-id').val('');
            $('#current-photo-link').html('');
            nomineeInformationAccordionCollapse.show();
        });

        // -------------------------
        // Cancel button
        // -------------------------
        $('#cancel-nominee-information-edit-btn').click(function() {
            nomineeInformationForm[0].reset();
            $('#nominee-information-id').val('');
            $('#current-photo-link').html('');
            nomineeInformationAccordionCollapse.hide();
        });

        // -------------------------
        // Save Nominee Information (Add/Edit)
        // -------------------------
        nomineeInformationForm.submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const nomineeId = $('#nominee-information-id').val();
            const url = nomineeId ? `/nomineeInformation/${nomineeId}` : '/nomineeInformation';
            const method = 'POST';

            if (nomineeId) formData.append('_method', 'PATCH');

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    loadNomineeInformation();
                    alert(response.message);
                    nomineeInformationForm[0].reset();
                    $('#nominee-information-id').val('');
                    $('#current-photo-link').html('');
                    nomineeInformationAccordionCollapse.hide();
                     // Reload table dynamically
                },
                error: function(xhr) {
                    alert('Error saving nominee information: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Edit Nominee Information
        // -------------------------
        $(document).on('click', '.edit-nominee-information', function() {
            const nomineeId = $(this).data('id');

            $.ajax({
                url: `/nomineeInformation/${nomineeId}/edit`,
                type: 'GET',
                success: function(response) {
                    loadNomineeInformation();
                    const nominee = response.nomineeInformation;
                    $('#nominee-information-id').val(nominee.id);
                    $('#nominee_name').val(nominee.nominee_name);
                    $('#relation').val(nominee.relation);
                    $('#voter_id').val(nominee.voter_id);
                    $('#percentage').val(nominee.percentage);

                    if (nominee.photo) {
                        $('#current-photo-link').html(`<a href="${nominee.photo}" target="_blank">View Current Photo</a>`);
                    } else {
                        $('#current-photo-link').html('');
                    }

                    nomineeInformationAccordionCollapse.show();
                },
                error: function(xhr) {
                    alert('Error fetching nominee information: ' + xhr.responseText);
                }
            });
        });

        // -------------------------
        // Delete Nominee Information
        // -------------------------
        $(document).on('click', '.delete-nominee-information', function() {
            const nomineeId = $(this).data('id');

            if (!confirm('Are you sure you want to delete this nominee information?')) return;

            $.ajax({
                url: `/nomineeInformation/${nomineeId}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    loadNomineeInformation();
                    alert(response.message);
                    loadNomineeInformation(); // Reload table dynamically
                },
                error: function(xhr) {
                    alert('Error deleting nominee information: ' + xhr.responseText);
                }
            });
        });

    });
</script>

@endpush
