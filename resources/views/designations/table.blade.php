<div class="table-responsive">
    <table class="table table-hover" id="designations-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Name</th>
        <th>Status</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($designations as $key => $designation)
            <tr>
                <td>{{ $designation->id }}</td>
            <td>{{ $designation->desi_name }}</td>
            <td>{{ $designation->desi_status }}</td>

                                <td>
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
