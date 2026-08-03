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
                <td>{{ method_exists($rewardings, 'firstItem') && $rewardings->firstItem() ? $rewardings->firstItem() + $key : $key + 1 }}</td>
                <td>{{ $rewarding->user->name ?? 'N/A' }} {{ $rewarding->user->last_name ?? '' }}</td>
            <td>{{ $rewarding->title }}</td>
            <td>
                         <a class="btn btn-primary" href="{{ $rewarding->document }}" target="_blank">View</a>
                        <a class="btn btn-primary" href="{{ $rewarding->document }}" download>Download</a>
            </td>
            <td>{{ $rewarding->date }}</td>
            <td>{{ $rewarding->reason }}</td>
            <td>{{ $rewarding->description }}</td>
           
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('rewardings.show', [$rewarding->id]),
                        'editRoute' => route('rewardings.edit', [$rewarding->id]),
                        'deleteRoute' => route('rewardings.destroy', [$rewarding->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
