<div class="table-responsive">
    <table class="table table-hover table-striped table_data" id="holydays-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Branch</th>
                <th>Title</th>
                <th>Status</th>
                <th>Date</th>
                <th>Description</th>
                <th data-orderable="false">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($holydays as $key => $holyday)
            <tr>
                <td>{{ method_exists($holydays, 'firstItem') && $holydays->firstItem() ? $holydays->firstItem() + $key : $key + 1 }}</td>
                <td>{{ $holyday->branch ? $holyday->branch->branch_name : 'All Branches' }}</td>
                <td>{{ $holyday->title }}</td>
                <td>
                    <span class="badge bg-{{ $holyday->status == 'Published' ? 'success' : 'warning' }}">
                        {{ $holyday->status }}
                    </span>
                </td>
                <td>{{ $holyday->date ? \Carbon\Carbon::parse($holyday->date)->format('d M, Y') : 'N/A' }}</td>
                <td>{{ $holyday->descreption }}</td>
                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('holydays.show', [$holyday->id]),
                        'editRoute' => route('holydays.edit', [$holyday->id]),
                        'deleteRoute' => route('holydays.destroy', [$holyday->id]),
                        'deleteConfirm' => 'Are you sure you want to delete this holiday?'
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
