<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Transfer Date</th>
                <th>Old Branch</th>
                <th>New Branch</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody id="transfer-details-table-body">
            @if(isset($users) && $users->transferDetails->count() > 0)
                @foreach($users->transferDetails as $transferDetail)
                    <tr data-id="{{ $transferDetail->id }}">
                        <td>{{ $transferDetail->transfer_date }}</td>
                        <td>{{ $transferDetail->old_branch }}</td>
                        <td>{{ $transferDetail->new_branch }}</td>
                        <td>{{ $transferDetail->reason }}</td>
                        <td>{{ $transferDetail->status }}</td>
                        <td>
                            @if($transferDetail->document)
                                <a href="{{ asset($transferDetail->document) }}" target="_blank">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No transfer details found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>