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
            </tr>
        </thead>
        <tbody id="training-details-table-body">
            @if(isset($users) && $users->trainingDetails->count() > 0)
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
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No training details found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>