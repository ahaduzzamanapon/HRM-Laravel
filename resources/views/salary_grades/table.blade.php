<div class="table-responsive">
    <table class="table" id="salaryGrades-table dataTable">
        <thead>
            <tr>
                <th>Sl</th>
        <th>Grade</th>
        <th>Starting Salary</th>
        <th>End Salary</th>
        <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($salaryGrades as $key => $salaryGrade)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $salaryGrade->grade }}</td>
            <td>{{ $salaryGrade->starting_salary }}</td>
            <td>{{ $salaryGrade->end_salary }}</td>
            <td>{{ $salaryGrade->description }}</td>
                                <td>
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('salaryGrades.show', [$salaryGrade->id]),
                        'editRoute' => route('salaryGrades.edit', [$salaryGrade->id]),
                        'deleteRoute' => route('salaryGrades.destroy', [$salaryGrade->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
