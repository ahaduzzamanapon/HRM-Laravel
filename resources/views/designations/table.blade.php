<div class="table-responsive">
    <table class="table table-hover align-middle" id="designations-table">
        <thead class="table-light">
            <tr>
                <th>SL</th>
                <th>Designation Name</th>
                <th>Status</th>
                <th class="text-end pe-3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($designations as $key => $designation)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td class="fw-bold">{{ $designation->desi_name }}</td>
                <td>
                    @if($designation->desi_status == 'Active')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td class="text-end pe-3">
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('designations.show', [$designation->id]),
                        'editRoute' => route('designations.edit', [$designation->id]),
                        'deleteRoute' => route('designations.destroy', [$designation->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
