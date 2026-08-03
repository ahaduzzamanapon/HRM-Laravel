<div class="table-responsive">
    <table class="table" id="departmental-cases-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Allegation Type</th>
                <th>Allegation Category</th>
                <th>Penalty</th>
                <th>Final Action Taken</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($departmentalCases as $departmentalCase)
            <tr>
                <td>{{ $departmentalCase->employee->name }}</td>
                <td>{{ $departmentalCase->allegation_type }}</td>
                <td>{{ $departmentalCase->allegation_category }}</td>
                <td>{{ $departmentalCase->penalty->name ?? 'N/A' }}</td>
                <td>{{ $departmentalCase->final_action_taken }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('departmentalCases.show', [$departmentalCase->id]),
                        'editRoute' => route('departmentalCases.edit', [$departmentalCase->id]),
                        'deleteRoute' => route('departmentalCases.destroy', [$departmentalCase->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
