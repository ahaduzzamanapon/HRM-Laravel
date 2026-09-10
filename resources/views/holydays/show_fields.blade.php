<tr>
    <th scope="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $holyday->id }}</td>
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
    <td>
        @if($holyday->date)
            @if($holyday->end_date && \Carbon\Carbon::parse($holyday->end_date)->format('Y-m-d') != \Carbon\Carbon::parse($holyday->date)->format('Y-m-d'))
                {{ \Carbon\Carbon::parse($holyday->date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($holyday->end_date)->format('d M, Y') }}
            @else
                {{ \Carbon\Carbon::parse($holyday->date)->format('d M, Y') }}
            @endif
        @else
            N/A
        @endif
    </td>
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


