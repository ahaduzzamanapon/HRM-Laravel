<div class="table-responsive">
    <table class="table" id="rewardings-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>User</th>
        <th>Title</th>
        <th>Document</th>
        <th>Date</th>
        <th>Reason</th>
        <th>Description</th>
      
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rewardings as $key => $rewarding)
            <tr>
                <td>{{ $rewarding->id }}</td>
                <td>{{ $rewarding->user->name }}</td>
            <td>{{ $rewarding->title }}</td>
            <td>
                         <a class="btn btn-primary" href="{{ $rewarding->document }}" target="_blank">View</a>
                        <a class="btn btn-primary" href="{{ $rewarding->document }}" download>Download</a>
            </td>
            <td>{{ $rewarding->date }}</td>
            <td>{{ $rewarding->reason }}</td>
            <td>{{ $rewarding->description }}</td>
           
                <td>
                    {!! Form::open(['route' => ['rewardings.destroy', $rewarding->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('rewardings.show', [$rewarding->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('rewardings.edit', [$rewarding->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
