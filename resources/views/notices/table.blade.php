<div class="table-responsive">
    <table class="table" id="notices-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Title</th>
                <th>Status</th>
                <th>Documents</th>
                <th>Description</th>

                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notices as $key => $notice)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $notice->title }}</td>
                    <td>{{ $notice->status }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ $notice->documents }}" target="_blank">View</a>
                        <a class="btn btn-primary" href="{{ $notice->documents }}" download>Download</a>
                    </td>
                    <td>{{ $notice->description }}</td>
                                    <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('notices.show', [$notice->id]),
                        'editRoute' => route('notices.edit', [$notice->id]),
                        'deleteRoute' => route('notices.destroy', [$notice->id]),
                    ])
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>