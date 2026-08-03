<div class="table-responsive">
    <table class="table" id="funeral-supports-table">
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
        @foreach($funeralSupports as $key => $funeralSupport)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $funeralSupport->employee->name }}</td>
                <td>{{ $funeralSupport->amount }}</td>
                <td>{{ $funeralSupport->support_date }}</td>
                <td>{{ $funeralSupport->remarks }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('funeralSupports.show', [$funeralSupport->id]),
                        'editRoute' => route('funeralSupports.edit', [$funeralSupport->id]),
                        'deleteRoute' => route('funeralSupports.destroy', [$funeralSupport->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
