<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Increment Date</th>
                <th>Old Salary</th>
                <th>New Salary</th>
                <th>Increment Amount</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody id="salary-increments-table-body">
            @if(isset($users) && $users->salaryIncrements->count() > 0)
                @foreach($users->salaryIncrements as $salaryIncrement)
                    <tr data-id="{{ $salaryIncrement->id }}">
                        <td>{{ $salaryIncrement->increment_date }}</td>
                        <td>{{ $salaryIncrement->old_salary }}</td>
                        <td>{{ $salaryIncrement->new_salary }}</td>
                        <td>{{ $salaryIncrement->increment_amount }}</td>
                        <td>
                            @if($salaryIncrement->document)
                                <a href="{{ asset($salaryIncrement->document) }}" target="_blank">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No salary increment details found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>