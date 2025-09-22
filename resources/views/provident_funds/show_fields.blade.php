<h4>Total Balance: {{ $user->provident_fund_balance }}</h4>

<table class="table">
    <thead>
        <tr>
            <th>Contribution Date</th>
            <th>Employee Contribution</th>
            <th>Employer Contribution</th>
        </tr>
    </thead>
    <tbody>
        @foreach($user->providentFundContributions as $contribution)
            <tr>
                <td>{{ $contribution->contribution_date }}</td>
                <td>{{ $contribution->employee_contribution }}</td>
                <td>{{ $contribution->employer_contribution }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
