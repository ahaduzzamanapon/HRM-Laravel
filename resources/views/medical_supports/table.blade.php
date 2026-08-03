<div class="table-responsive">
    <table class="table" id="medical-supports-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Amount</th>
                <th>Support Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($medicalSupports as $key => $medicalSupport)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $medicalSupport->employee->name }}</td>
                <td>{{ $medicalSupport->amount }}</td>
                <td>{{ $medicalSupport->support_date }}</td>
                <td>{{ $medicalSupport->remarks }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('medicalSupports.show', [$medicalSupport->id]),
                        'editRoute' => route('medicalSupports.edit', [$medicalSupport->id]),
                        'deleteRoute' => route('medicalSupports.destroy', [$medicalSupport->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
