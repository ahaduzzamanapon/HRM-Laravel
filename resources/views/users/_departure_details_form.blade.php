<div class="row">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="col-md-10">Departure details (Left / Resign / Retired)</h4>
            <button id="add-departure-btn" class="btn btn-primary btn-sm col-md-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeparture" aria-expanded="false" aria-controls="collapseDeparture" style="{{ (isset($users) && $users->departures->count() > 0) ? 'display: none;' : '' }}">
             <i class="im im-icon-Add"></i> Add New Record</button>
        </div>

        <!-- Accordion Form for Add/Edit -->
        <div class="accordion mb-4" id="departureAccordion">
            <div class="accordion-item">
                <div id="collapseDeparture" class="accordion-collapse collapse" aria-labelledby="headingDeparture" data-bs-parent="#departureAccordion">
                    <div class="accordion-body" style="border: 1px solid #ddd; padding: 15px; border-radius: 4px; background-color: #f9f9f9; margin-bottom: 20px;">
                        <form id="departure-detail-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="departure-detail-id">
                            <input type="hidden" name="user_id" value="{{ $users->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departure_status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="departure_status" class="form-control" required>
                                            <option value="left">Left</option>
                                            <option value="resign">Resign</option>
                                            <option value="retired">Retired</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departure_effective_date">Effective Date <span class="text-danger">*</span></label>
                                        <input type="date" name="effective_date" id="departure_effective_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departure_reason">Reason</label>
                                        <textarea name="reason" id="departure_reason" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="departure_remarks">Remarks</label>
                                        <textarea name="remarks" id="departure_remarks" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document_departure">Document (e.g. Resignation Letter, Retirement Clearance)</label>
                                        <input type="file" name="document" id="document_departure" class="form-control">
                                        <span id="current-document-link-departure" class="d-block mt-2"></span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" id="save-departure-detail-btn">Save Record</button>
                            <button type="button" class="btn btn-secondary" id="cancel-departure-edit-btn">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Existing Departure Details in a Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Effective Date</th>
                        <th>Reason</th>
                        <th>Remarks</th>
                        <th>Document</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="departure-details-table-body">
                    @if( isset($users) && $users->departures->count() > 0 )
                        @foreach($users->departures as $departure)
                            <tr data-id="{{ $departure->id }}">
                                <td><span class="badge bg-{{ $departure->status == 'retired' ? 'primary' : ($departure->status == 'resign' ? 'warning' : 'secondary') }}">{{ ucfirst($departure->status) }}</span></td>
                                <td>{{ $departure->effective_date }}</td>
                                <td>{{ $departure->reason ?? 'N/A' }}</td>
                                <td>{{ $departure->remarks ?? 'N/A' }}</td>
                                <td>
                                    @if($departure->document)
                                        <a href="{{ asset($departure->document) }}" target="_blank" class="btn btn-xs btn-outline-info"><i class="im im-icon-File"></i> View</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-info edit-departure-detail" data-id="{{ $departure->id }}"><i class="im im-icon-Pen"></i> Edit</button>
                                    <button type="button" class="btn btn-xs btn-danger delete-departure-detail" data-id="{{ $departure->id }}"><i class="im im-icon-Trash"></i> Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-muted">No departure tracking records found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const departureUserId = "{{ $users->id }}";
        
        function loadDepartureDetails() {
            $.ajax({
                url: `/employeeDepartures/list/${departureUserId}`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    let tableData = '';
                    if (res.employeeDeparture && res.employeeDeparture.length > 0) {
                        $('#add-departure-btn').hide();
                        res.employeeDeparture.forEach(departure => {
                            let badgeClass = departure.status === 'retired' ? 'primary' : (departure.status === 'resign' ? 'warning' : 'secondary');
                            let statusText = departure.status.charAt(0).toUpperCase() + departure.status.slice(1);
                            tableData += `<tr data-id="${departure.id}">
                                <td><span class="badge bg-${badgeClass}">${statusText}</span></td>
                                <td>${departure.effective_date}</td>
                                <td>${departure.reason ? departure.reason : 'N/A'}</td>
                                <td>${departure.remarks ? departure.remarks : 'N/A'}</td>
                                <td>${departure.document ? `<a href="/${departure.document}" target="_blank" class="btn btn-xs btn-outline-info"><i class="im im-icon-File"></i> View</a>` : 'N/A'}</td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-info edit-departure-detail" data-id="${departure.id}"><i class="im im-icon-Pen"></i> Edit</button>
                                    <button type="button" class="btn btn-xs btn-danger delete-departure-detail" data-id="${departure.id}"><i class="im im-icon-Trash"></i> Delete</button>
                                </td>
                            </tr>`;
                        });
                    } else {
                        $('#add-departure-btn').show();
                        tableData = '<tr><td colspan="6" class="text-center text-muted">No departure tracking records found.</td></tr>';
                    }
                    $('#departure-details-table-body').html(tableData);
                },
                error: function(xhr) {
                    console.error('Error loading departure table:', xhr.responseText);
                }
            });
        }

        $(document).ready(function() {
            const departureForm = $('#departure-detail-form');
            const departureAccordionCollapse = new bootstrap.Collapse($('#collapseDeparture'), { toggle: false });

            // Cancel button for form
            $('#cancel-departure-edit-btn').click(function() {
                departureForm[0].reset();
                $('#departure-detail-id').val('');
                $('#current-document-link-departure').html('');
                departureAccordionCollapse.hide();
            });

            // Save Departure Detail (Add/Edit)
            departureForm.submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const departureDetailId = $('#departure-detail-id').val();
                const url = departureDetailId ? `/employeeDepartures/${departureDetailId}` : '/employeeDepartures';
                
                if (departureDetailId) {
                    formData.append('_method', 'PATCH');
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        loadDepartureDetails();
                        if(response.error) {
                            alert('Error: ' + response.message);
                        } else {
                            alert(response.message);
                            departureForm[0].reset();
                            $('#departure-detail-id').val('');
                            $('#current-document-link-departure').html('');
                            departureAccordionCollapse.hide();
                        }
                    },
                    error: function(xhr) {
                        alert('Error saving departure detail: ' + xhr.responseText);
                    }
                });
            });

            // Edit Departure Detail
            $(document).on('click', '.edit-departure-detail', function() {
                const departureDetailId = $(this).data('id');
                $.ajax({
                    url: `/employeeDepartures/${departureDetailId}/edit`,
                    type: 'GET',
                    success: function(response) {
                        $('#departure-detail-id').val(response.employeeDeparture.id);
                        $('#departure_status').val(response.employeeDeparture.status);
                        $('#departure_effective_date').val(response.employeeDeparture.effective_date);
                        $('#departure_reason').val(response.employeeDeparture.reason);
                        $('#departure_remarks').val(response.employeeDeparture.remarks);
                        if (response.employeeDeparture.document) {
                            $('#current-document-link-departure').html(`<a href="/${response.employeeDeparture.document}" target="_blank" class="text-info"><i class="im im-icon-File"></i> View Current Document</a>`);
                        } else {
                            $('#current-document-link-departure').html('');
                        }
                        departureAccordionCollapse.show();
                    },
                    error: function(xhr) {
                        alert('Error fetching departure detail: ' + xhr.responseText);
                    }
                });
            });

            // Delete Departure Detail
            $(document).on('click', '.delete-departure-detail', function() {
                if (confirm('Are you sure you want to delete this departure tracking record?')) {
                    const departureDetailId = $(this).data('id');
                    $.ajax({
                        url: `/employeeDepartures/${departureDetailId}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            loadDepartureDetails();
                            alert(response.message);
                        },
                        error: function(xhr) {
                            alert('Error deleting departure detail: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
