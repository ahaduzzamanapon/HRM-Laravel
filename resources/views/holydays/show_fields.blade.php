<tr>
    <th scope="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $holyday->id }}</td>
</tr>

<tr>
    <th scope="row">{!! Form::label('branch_id', 'Branch:') !!}</th>
    <td>{{ $holyday->branch ? $holyday->branch->branch_name : 'All Branches' }}</td>
</tr>

<tr>
    <th scope="row">{!! Form::label('title', 'Title:') !!}</th>
    <td>{{ $holyday->title }}</td>
</tr>

<tr>
    <th scope="row">{!! Form::label('status', 'Status:') !!}</th>
    <td>
        <span class="badge bg-{{ $holyday->status == 'Published' ? 'success' : 'warning' }}">
            {{ $holyday->status }}
        </span>
    </td>
</tr>

<tr>
    <th scope="row">{!! Form::label('date', 'Date:') !!}</th>
    <td>{{ $holyday->date ? \Carbon\Carbon::parse($holyday->date)->format('d M, Y') : 'N/A' }}</td>
</tr>

<tr>
    <th scope="row">{!! Form::label('descreption', 'Description:') !!}</th>
    <td>{{ $holyday->descreption }}</td>
</tr>


<tr>
    <th scope="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $holyday->created_at }}</td>
</tr>

<tr>
    <th scope="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $holyday->updated_at }}</td>
</tr>


