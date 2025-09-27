<div class="table-responsive">
    <table class="table" id="taxSetups-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Titel</th>
        <th>Min Salary</th>
        <th>Max Salary</th>
        <th>Tax Yearly</th>
        <th>Tax Monthly</th>
        <th>Update By</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($taxSetups as $key => $taxSetup)
            <tr>
                <td>{{ $taxSetup->id }}</td>
            <td>{{ $taxSetup->titel }}</td>
            <td>{{ $taxSetup->min_salary }}</td>
            <td>{{ $taxSetup->max_salary }}</td>
            <td>{{ $taxSetup->tax_yearly }}</td>
            <td>{{ $taxSetup->tax_monthly }}</td>
            <td>{{ $taxSetup->update_by }}</td>
            <td>{{ $taxSetup->created_at }}</td>
            <td>{{ $taxSetup->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['taxSetups.destroy', $taxSetup->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('taxSetups.show', [$taxSetup->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('taxSetups.edit', [$taxSetup->id]) }}" class='btn btn-outline-primary btn-xs'><i
                                class="im im-icon-Pen"  data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                        {!! Form::button('<i class="im im-icon-Remove" data-toggle="tooltip" data-placement="top" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
