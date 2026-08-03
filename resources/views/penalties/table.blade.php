<div class="table-responsive">
    <table class="table" id="penalties-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($penalties as $penalty)
            <tr>
                <td>{{ $penalty->name }}</td>
                <td>{{ $penalty->type }}</td>
                <td>{{ $penalty->description }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('penalties.show', [$penalty->id]),
                        'editRoute' => route('penalties.edit', [$penalty->id]),
                        'deleteRoute' => route('penalties.destroy', [$penalty->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
