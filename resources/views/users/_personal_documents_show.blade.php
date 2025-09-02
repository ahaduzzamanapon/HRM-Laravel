<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Document Type</th>
                <th>Document File</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="personal-documents-table-body">
            @if(isset($users) && $users->personalDocuments->count() > 0)
                @foreach($users->personalDocuments as $personalDocument)
                    <tr data-id="{{ $personalDocument->id }}">
                        <td>{{ $personalDocument->document_type }}</td>
                        <td>
                            @if($personalDocument->document_file)
                                <a href="{{ asset($personalDocument->document_file) }}" target="_blank">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $personalDocument->description }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center">No personal documents found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>