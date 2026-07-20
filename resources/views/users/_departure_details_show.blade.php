<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Status</th>
                <th>Effective Date</th>
                <th>Reason</th>
                <th>Remarks</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($users) && $users->departures->count() > 0)
                @foreach($users->departures as $departure)
                    <tr>
                        <td><span class="badge bg-{{ $departure->status == 'retired' ? 'primary' : ($departure->status == 'resign' ? 'warning' : 'secondary') }}">{{ ucfirst($departure->status) }}</span></td>
                        <td>{{ $departure->effective_date }}</td>
                        <td>{{ $departure->reason ?? 'N/A' }}</td>
                        <td>{{ $departure->remarks ?? 'N/A' }}</td>
                        <td>
                            @if($departure->document)
                                <a href="{{ asset($departure->document) }}" target="_blank" class="btn btn-xs btn-outline-info"><i class="im im-icon-File"></i> View</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center text-muted">No departure tracking records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
