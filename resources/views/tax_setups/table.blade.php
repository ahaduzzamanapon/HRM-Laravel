<div class="table-responsive">
    <table class="table" id="taxSetups-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Title</th>
        <th>Min Salary</th>
        <th>Max Salary</th>
        <th>Tax Yearly</th>
        <th>Tax Monthly</th>
        <th>Update By</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($taxSetups as $key => $taxSetup)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $taxSetup->title }}</td>
            <td>{{ $taxSetup->min_salary }}</td>
            <td>{{ $taxSetup->max_salary }}</td>
            <td>{{ $taxSetup->tax_yearly }}</td>
            <td>{{ $taxSetup->tax_monthly }}</td>
            <td>{{ $taxSetup->updater->name ?? 'N/A' }}</td>
            <td>{{ $taxSetup->updated_at }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('taxSetups.show', [$taxSetup->id]),
                        'editRoute' => route('taxSetups.edit', [$taxSetup->id]),
                        'deleteRoute' => route('taxSetups.destroy', [$taxSetup->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
