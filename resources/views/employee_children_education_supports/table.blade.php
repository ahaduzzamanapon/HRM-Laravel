<div class="table-responsive">
    <table class="table" id="employee-children-education-supports-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee</th>
                <th>Child Name</th>
                <th>Exam Name</th>
                <th>GPA</th>
                <th>Financial Assistance</th>
                <th>Support Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employeeChildrenEducationSupports as $key => $employeeChildrenEducationSupport)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $employeeChildrenEducationSupport->employee->name }}</td>
                <td>{{ $employeeChildrenEducationSupport->child_name }}</td>
                <td>{{ $employeeChildrenEducationSupport->exam_name }}</td>
                <td>{{ $employeeChildrenEducationSupport->gpa }}</td>
                <td>{{ $employeeChildrenEducationSupport->financial_assistance }}</td>
                <td>{{ $employeeChildrenEducationSupport->support_date }}</td>
                <td>{{ $employeeChildrenEducationSupport->remarks }}</td>
                <td>
                    {!! Form::open(['route' => ['employeeChildrenEducationSupports.destroy', $employeeChildrenEducationSupport->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('employeeChildrenEducationSupports.show', [$employeeChildrenEducationSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('employeeChildrenEducationSupports.edit', [$employeeChildrenEducationSupport->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
