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
                    @include('layouts.partials.action_buttons', [
                        'viewRoute' => route('employeeChildrenEducationSupports.show', [$employeeChildrenEducationSupport->id]),
                        'editRoute' => route('employeeChildrenEducationSupports.edit', [$employeeChildrenEducationSupport->id]),
                        'deleteRoute' => route('employeeChildrenEducationSupports.destroy', [$employeeChildrenEducationSupport->id]),
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
