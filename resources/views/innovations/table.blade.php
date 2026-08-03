<div class="table-responsive">
    <table class="table" id="innovations-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Title</th>
                <th>Innovation Type</th>
                <th>Submission Date</th>
                <th>Verifier</th>
                <th>Verification Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($innovations as $innovation)
            <tr>
                <td>{{ $innovation->employee->name }}</td>
                <td>{{ $innovation->title }}</td>
                <td>{{ $innovation->innovation_type }}</td>
                <td>{{ $innovation->submission_date }}</td>
                <td>{{ $innovation->verifier->name }}</td>
                <td>{{ $innovation->verification_status }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('innovations.show', [$innovation->id]),
                        'editRoute' => route('innovations.edit', [$innovation->id]),
                        'deleteRoute' => route('innovations.destroy', [$innovation->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
