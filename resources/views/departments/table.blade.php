<div class="table-responsive">
    <table class="table table-hover align-middle" id="departments-table">
        <thead class="table-light">
            <tr>
                <th>Sl</th>
                <th>Department Name</th>
                <th>Status</th>
                <th class="text-end pe-3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($departments as $key => $department)
            <tr>
                <td>{{ $key+1 }}</td>
                <td class="fw-bold">{{ $department->name }}</td>
                <td>
                    @if($department->status == 'Active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td class="text-end pe-3">
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
