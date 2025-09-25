<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProvidentFundContribution;
use App\Models\User;

class ProvidentFundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $query = User::where('is_pf_member', true);

        if ($user->role->name == 'Employee') {
            $query->where('id', $user->id);
        }

        $users = $query->paginate(10);
        return view('provident_funds.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('providentFundContributions')->find($id);
        $authUser = \Illuminate\Support\Facades\Auth::user();

        if ($authUser->role->name == 'Employee' && $authUser->id != $id) {
            Flash::error('You are not authorized to view this page.');
            return redirect(route('providentFunds.index'));
        }

        if (empty($user) || !$user->is_pf_member) {
            Flash::error('Provident Fund member not found');
            return redirect(route('providentFunds.index'));
        }
        return view('provident_funds.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
