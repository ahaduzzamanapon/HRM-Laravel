<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Promotion Date</th>
                <th>New Designation</th>
                <th>Old Designation</th>
                <th>Pay Grade Change</th>
                <th>New Salary</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody id="promotion-details-table-body">
            @if(isset($users) && $users->promotionDetails->count() > 0)
                @foreach($users->promotionDetails as $promotionDetail)
                    <tr data-id="{{ $promotionDetail->id }}">
                        <td>{{ $promotionDetail->promotion_date }}</td>
                        <td>{{ $promotionDetail->new_designation }}</td>
                        <td>{{ $promotionDetail->old_designation }}</td>
                        <td>{{ $promotionDetail->pay_grade_change ? 'Yes' : 'No' }}</td>
                        <td>{{ $promotionDetail->new_salary }}</td>
                        <td>
                            @if($promotionDetail->document)
                                <a href="{{ asset($promotionDetail->document) }}" target="_blank">View</a>
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No promotion details found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>