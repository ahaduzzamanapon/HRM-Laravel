<div class="table-responsive">
    <table class="table" id="branches-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Branch Name</th>
        <th>Address</th>
        <th>Status</th>
        <th>Description</th>
     
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($branches as $key => $branch)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $branch->branch_name }}</td>
            <td>{{ $branch->Address }}</td>
            <td>{{ $branch->status }}</td>
            <td>{{ $branch->description }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('branches.show', [$branch->id]),
                        'editRoute' => route('branches.edit', [$branch->id]),
                        'deleteRoute' => route('branches.destroy', [$branch->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
