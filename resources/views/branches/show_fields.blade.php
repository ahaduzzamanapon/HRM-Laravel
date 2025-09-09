<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $branch->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('branch_name', 'Branch Name:') !!}</th>
    <td>{{ $branch->branch_name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('Address', 'Address:') !!}</th>
    <td>{{ $branch->Address }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('status', 'Status:') !!}</th>
    <td>{{ $branch->status }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('description', 'Description:') !!}</th>
    <td>{{ $branch->description }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $branch->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $branch->updated_at }}</td>
</tr>


