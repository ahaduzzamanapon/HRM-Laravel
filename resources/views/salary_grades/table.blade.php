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
                    {!! Form::open(['route' => ['salaryGrades.destroy', $salaryGrade->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('salaryGrades.show', [$salaryGrade->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('salaryGrades.edit', [$salaryGrade->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
