<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Flash;
use Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $users */
        $users = User::select('users.*', 'roles.name as role', 'designations.desi_name as designation')
            ->leftjoin('roles', 'users.group_id', '=', 'roles.id')
            ->leftjoin('designations', 'users.designation_id', '=', 'designations.id')
            ->paginate(10);

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }


    public function store(Request $request)
    {
        $input = $request->all();


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            $input['image'] = 'no-image.png';
        }

        if ($request->has('password')) {
            $input['password'] = bcrypt($request->password);
        }else{
            $input['password'] = bcrypt('12345678');
        }



        /** @var User $users */
        $users = User::create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }


    public function show($id)
    {
        /** @var User $users */
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('users', $users);
    }


    public function edit($id)
    {
        /** @var User $users */
        $users = User::find($id);
        //

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('users', $users);
    }


    public function update($id, Request $request)
    {
        /** @var User $users */
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $input = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['image']);
        }
        if ($request->has('password') && !empty($request->password)) {
            $input['password'] = bcrypt($request->password);
        }else{
            unset($input['password']);
        }
        $users->fill($input);
        $users->save();
        Flash::success('User updated successfully.');
        return redirect(route('users.index'));
    }

    public function destroy($id)
    {
        /** @var User $users */
        $users = User::find($id);
        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        $users->delete();
        Flash::success('User deleted successfully.');
        return redirect(route('users.index'));
    }
}
