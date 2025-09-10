<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center">
            <h4 class="col-md-10">Training Details</h4>
            <button class="btn btn-primary btn-sm col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
             <i class="im im-icon-Add"></i>  Add New</button>
        </div>

        <!-- Accordion Form for Add/Edit (moved to top) -->
        <div class="accordion mb-4" id="trainingAccordion">
            <div class="accordion-item">
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#trainingAccordion">
                    <div class="accordion-body">
                        <form id="training-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="training-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="training_name">Training Name:</label>
                                        <input type="text" name="training_name" id="training_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="training_provider">Training Provider:</label>
                                        <input type="text" name="training_provider" id="training_provider" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="training_type">Training Type:</label>
                                        <select name="training_type" id="training_type" class="form-control">
                                            <option value="Domestic">Domestic</option>
                                            <option value="Foreign">Foreign</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date:</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date:</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document">Document:</label>
                                        <input type="file" name="document" id="document" class="form-control">
                                        <span id="current-document-link"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-training-detail-btn">Save Training</button>
                            <button type="button" class="btn btn-secondary" id="cancel-training-edit-btn" data-toggle="collapse" data-target="#collapseOne">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Training Details in a Table (moved to bottom) -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Training Name</th>
                        <th>Provider</th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="training-details-table-body">
                    {{-- @dd($users) --}}
                    @if( isset($users) && $users->trainingDetails->count() > 0 )
                        @foreach($users->trainingDetails as $trainingDetail)
                            <tr data-id="{{ $trainingDetail->id }}">
                                <td>{{ $trainingDetail->training_name }}</td>
                                <td>{{ $trainingDetail->training_provider }}</td>
                                <td>{{ $trainingDetail->training_type }}</td>
                                <td>{{ $trainingDetail->start_date }}</td>
                                <td>{{ $trainingDetail->end_date }}</td>
                                <td>
                                    @if($trainingDetail->document)
                                        <a href="{{ asset($trainingDetail->document) }}" target="_blank">View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info edit-training-detail" data-id="{{ $trainingDetail->id }}">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-training-detail" data-id="{{ $trainingDetail->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">No training details found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        const userId = "{{ $users->id }}";
        function loadTrainingDetails() {
            $.ajax({
                url: `/trainingDetails/list/${userId}`, // Laravel route
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    // console.log(res.trainingDetail[0]);
                    let tableData = '';
                    res.trainingDetail.forEach(trainingDetail => {
                        tableData += `<tr data-id="${trainingDetail.id}">
                            <td>${trainingDetail.training_name}</td>
                            <td>${trainingDetail.training_provider}</td>
                            <td>${trainingDetail.training_type}</td>
                            <td>${trainingDetail.start_date}</td>
                            <td>${trainingDetail.end_date}</td>
                            <td>${trainingDetail.document ? `<a href="${trainingDetail.document}" target="_blank">View</a>` : 'N/A'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info edit-training-detail" data-id="${trainingDetail.id}">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger delete-training-detail" data-id="${trainingDetail.id}">Delete</button>
                            </td>
                        </tr>`;
                    });
                    $('#training-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading table:', xhr.responseText);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
        // Select all buttons that toggle collapse
            $('button[data-bs-toggle="collapse"]').each(function() {
                $(this).on('click', function() {
                        // Get the target accordion from data-bs-target
                    var targetSelector = $(this).data('bs-target');
                    var $collapseElement = $(targetSelector);

                    // Find a form inside the collapse element (if any)
                    var $form = $collapseElement.find('form');

                    // Reset the form if it exists
                    if ($form.length) {
                        $form[0].reset();
                    }
                });
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            const trainingForm = $('#training-detail-form');
            const trainingAccordionCollapse = new bootstrap.Collapse($('#collapseOne'), { toggle: false });

            // Cancel button for form
            $('#cancel-training-edit-btn').click(function() {
                trainingForm[0].reset();
                trainingAccordionCollapse.hide(); // Hide accordion
            });


            // Save Training Detail (Add/Edit)
            trainingForm.submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const trainingDetailId = $('#training-detail-id').val();
                const url = trainingDetailId ? `/trainingDetails/${trainingDetailId}` : '/trainingDetails';
                const method = trainingDetailId ? 'POST' : 'POST'; // Laravel uses POST for PUT/PATCH with _method field

                if (trainingDetailId) {
                    formData.append('_method', 'PATCH'); // Spoof PATCH method for Laravel
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        loadTrainingDetails();

                        if(response.error){
                            alert('Error: ' + response.message + ' !!!!');
                            return;
                        }else{
                            alert(response.message);
                            trainingAccordionCollapse.hide();
                            // location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Error saving training detail: ' + xhr.responseText);
                    }
                });
            });

            // Edit Training Detail
            $(document).on('click', '.edit-training-detail', function() {
                const trainingDetailId = $(this).data('id');
                $.ajax({
                    url: `/trainingDetails/${trainingDetailId}/edit`, // Laravel's edit route returns data for form
                    type: 'GET',
                    success: function(response) {
                        loadTrainingDetails();

                        $('#training-detail-id').val(response.trainingDetail.id);
                        $('#training_name').val(response.trainingDetail.training_name);
                        $('#training_provider').val(response.trainingDetail.training_provider);
                        $('#training_type').val(response.trainingDetail.training_type);
                        $('#start_date').val(response.trainingDetail.start_date);
                        $('#end_date').val(response.trainingDetail.end_date);
                        $('#description').val(response.trainingDetail.description);
                        if (response.trainingDetail.document) {
                            $('#current-document-link').html(`<a href="{{asset('${response.trainingDetail.document}')}}" target="_blank">View Current Document</a>`);
                        } else {
                            $('#current-document-link').html('');
                        }
                        trainingAccordionCollapse.show(); // Show accordion
                    },
                    error: function(xhr) {
                        alert('Error fetching training detail: ' + xhr.responseText);
                    }
                });
            });

            // Delete Training Detail
            $(document).on('click', '.delete-training-detail', function() {
                if (confirm('Are you sure you want to delete this training detail?')) {
                    const trainingDetailId = $(this).data('id');
                    $.ajax({
                        url: `/trainingDetails/${trainingDetailId}`,
                        type: 'POST', // Laravel uses POST for DELETE with _method field
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            loadTrainingDetails();

                            alert(response.message);
                            // Remove row from table
                            $(`tr[data-id="${trainingDetailId}"]`).remove();
                            // If no rows left, display "No training details found."
                            if ($('#training-details-table-body tr').length === 0) {
                                $('#training-details-table-body').html('<tr><td colspan="7" class="text-center">No training details found.</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            alert('Error deleting training detail: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>



@endpush
