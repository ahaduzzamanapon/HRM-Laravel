<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Transfer Employee: <span id="employeeName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['route' => 'transferDetails.store', 'files' => true, 'id' => 'transferForm']) !!}
            <div class="modal-body">
                <input type="hidden" name="user_id" id="transferUserId">
                <input type="hidden" name="old_branch" id="oldBranchId" value="{{ $users->branch->id ?? '' }}">

                <div class="mb-3">
                    <label for="transfer_date" class="form-label">Transfer Date:</label>
                    <input type="date" class="form-control" id="transferDate" name="transfer_date" required>
                </div>

                <div class="mb-3">
                    <label for="new_branch" class="form-label">To Branch:</label>
                    <select class="form-control" id="newBranch" name="new_branch">
                        <option value="">Select Branch</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason:</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="document" class="form-label">Document (Optional):</label>
                    <input type="file" class="form-control" id="document" name="document">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Transfer</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('scripts')
    <script>
    function openTransferModal(userId, userName, currentBranchId, currentBranchName) {
                const modal = $('#transferModal');
                modal.find('#employeeName').text(userName);
                modal.find('#transferUserId').val(userId);
                modal.find('#oldBranchId').val(currentBranchId);
                modal.find('#old_branch_display').val(currentBranchName); // Populate Old Branch text input

                modal.find('#newBranch option').each(function() {
                    if ($(this).val() == currentBranchId) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });

                modal.modal('show'); // Manually show the modal
            }
    </script>
@endpush

