<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $users->emp_id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('name', 'first Name:') !!}</th>
    <td>{{ $users->name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('last_name', 'Last Name:') !!}</th>
    <td>{{ $users->last_name }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('email', 'Email:') !!}</th>
    <td>{{ $users->email }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('date_of_birth', 'Date Of Birth:') !!}</th>
    <td>{{ $users->date_of_birth }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('date_of_join', 'Date Of Join:') !!}</th>
    <td>{{ $users->date_of_join }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('gender', 'Gender:') !!}</th>
    <td>{{ $users->gender }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('address', 'Address:') !!}</th>
    <td>{{ $users->address }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('phone_number', 'Phone Number:') !!}</th>
    <td>{{ $users->phone_number }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('image', 'Image:') !!}</th>
    <td><img src="{{ asset($users->image) }}" style="width: 100px; height: 100px; border-radius: 50%;" alt=""></td>
</tr>



<tr>
    <th scopre="row">{!! Form::label('group_id', 'Group Id:') !!}</th>
    <td>{{ $users->group_id }}</td>
</tr>




<tr>
    <th scopre="row">{!! Form::label('blood_group', 'Blood Group:') !!}</th>
    <td>{{ $users->blood_group }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('religion', 'Religion:') !!}</th>
    <td>{{ $users->religion }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('marital_status', 'Marital Status:') !!}</th>
    <td>{{ $users->marital_status }}</td>
</tr>



