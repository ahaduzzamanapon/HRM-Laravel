<div class="table-responsive">
    <table class="table" id="bankSetups-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Bank Name</th>
        <th>Branch Name</th>
        <th>Address</th>
        <th>Bank Code</th>
        <th>Description</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($bankSetups as $key => $bankSetup)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $bankSetup->bank_name }}</td>
            <td>{{ $bankSetup->branch_name }}</td>
            <td>{{ $bankSetup->address }}</td>
            <td>{{ $bankSetup->bank_code }}</td>
            <td>{{ $bankSetup->description }}</td>
            <td>{{ $bankSetup->created_at }}</td>
            <td>{{ $bankSetup->updated_at }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('bankSetups.show', [$bankSetup->id]),
                        'editRoute' => route('bankSetups.edit', [$bankSetup->id]),
                        'deleteRoute' => route('bankSetups.destroy', [$bankSetup->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
