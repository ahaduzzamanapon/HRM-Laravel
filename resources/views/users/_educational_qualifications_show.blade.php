<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Degree</th>
                <th>Institution</th>
                <th>Passing Year</th>
                <th>Grade</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody id="educational-qualifications-table-body">
            @if(isset($users) && $users->educationalQualifications->count() > 0)
                @foreach($users->educationalQualifications as $educationalQualification)
                    <tr data-id="{{ $educationalQualification->id }}">
                        <td>{{ $educationalQualification->degree }}</td>
                        <td>{{ $educationalQualification->institution }}</td>
                        <td>{{ $educationalQualification->passing_year }}</td>
                        <td>{{ $educationalQualification->grade }}</td>
                        <td>
                            @if($educationalQualification->document)
                                <a href="{{ asset($educationalQualification->document) }}" target="_blank">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No educational qualifications found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>