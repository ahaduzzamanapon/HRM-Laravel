<div class="table-responsive">
    <table class="table" id="departments-table">
        <thead>
            <tr>
                <th>Sl</th>
        <th>Name</th>
        <th>Status</th>
       
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($departments as $key => $department)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $department->name }}</td>
            <td>{{ $department->status }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('departments.show', [$department->id]),
                        'editRoute' => route('departments.edit', [$department->id]),
                        'deleteRoute' => route('departments.destroy', [$department->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
