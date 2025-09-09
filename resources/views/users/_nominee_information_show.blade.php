<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nominee Name</th>
                <th>Relation</th>
                <th>Voter ID</th>
                <th>Photo</th>
                <th>Percentage</th>
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
                                <img src="{{ asset($nomineeInformation->photo) }}" alt="Nominee Photo" width="50">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $nomineeInformation->percentage }}%</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No nominee information found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>