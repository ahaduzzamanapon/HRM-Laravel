<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Job Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="job-experience-table-body">
            @if(isset($users) && $users->jobExperiences->count() > 0)
                @foreach($users->jobExperiences as $jobExperience)
                    <tr data-id="{{ $jobExperience->id }}">
                        <td>{{ $jobExperience->company_name }}</td>
                        <td>{{ $jobExperience->job_title }}</td>
                        <td>{{ $jobExperience->start_date }}</td>
                        <td>{{ $jobExperience->end_date }}</td>
                        <td>{{ $jobExperience->description }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No job experience details found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>