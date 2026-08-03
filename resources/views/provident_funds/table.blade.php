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
                    @include('layouts.partials.action_buttons', [
                        'showEdit' => false,
                        'showDelete' => false,
                        'viewRoute' => route('providentFunds.show', [$user->id])
                    ])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
