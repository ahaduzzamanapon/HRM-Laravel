<div class="table-responsive">
    <table class="table" id="provident-funds-table">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Provident Fund Balance</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->provident_fund_balance }}</td>
                <td>
                    <a href="{{ route('providentFunds.show', [$user->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View Statement"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
