<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle"
                 src="{{ asset($users->image) }}"
                 alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ $users->name }} {{ $users->last_name }}</h3>

        {{-- <p class="text-muted text-center">{{ $users->designation }}</p> --}}

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>Email</b> <a class="float-right">{{ $users->email }}</a>
            </li>
            <li class="list-group-item">
                <b>Phone</b> <a class="float-right">{{ $users->phone_number }}</a>
            </li>
            <li class="list-group-item">
                <b>Date of Birth</b> <a class="float-right">{{ $users->date_of_birth }}</a>
            </li>
            <li class="list-group-item">
                <b>Date of Join</b> <a class="float-right">{{ $users->date_of_join }}</a>
            </li>
        </ul>

        <a href="{{ route('users.edit', [$users->id]) }}" class="btn btn-primary btn-block"><b>Edit</b></a>
    </div>
    <!-- /.card-body -->
</div>
