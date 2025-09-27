<div class="table-responsive">
    <table class="table" id="bankSetups-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Bank Name</th>
        <th>Branch Name</th>
        <th>Address</th>
        <th>Bank Code</th>
        <th>Description</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($bankSetups as $key => $bankSetup)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $bankSetup->bank_name }}</td>
            <td>{{ $bankSetup->branch_name }}</td>
            <td>{{ $bankSetup->address }}</td>
            <td>{{ $bankSetup->bank_code }}</td>
            <td>{{ $bankSetup->description }}</td>
            <td>{{ $bankSetup->created_at }}</td>
            <td>{{ $bankSetup->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['bankSetups.destroy', $bankSetup->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('bankSetups.show', [$bankSetup->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('bankSetups.edit', [$bankSetup->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
