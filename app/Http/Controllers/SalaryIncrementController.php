<?php

namespace App\Http\Controllers;

use App\Models\SalaryIncrement;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class SalaryIncrementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salaryIncrements = SalaryIncrement::paginate(10);
        return view('salary_increments.index')->with('salaryIncrements', $salaryIncrements);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('salary_increments.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');

        $user = User::find($input['user_id']);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $input['old_grade_id'] = $user->salary_grade_id;

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/salary_increments';
            $customName = 'salary-increment-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        SalaryIncrement::create($input);

        // Update user's salary structure
        $user->basic_salary = $input['new_salary'];
        $user->salary_grade_id = $input['new_grade_id'];
        $user->save();

        return response()->json(['message' => 'Salary Increment saved successfully.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salaryIncrement = SalaryIncrement::find($id);

        if (empty($salaryIncrement)) {
            return response()->json(['error' => 'Salary Increment not found'], 404);
        }

        return view('salary_increments.show')->with('salaryIncrement', $salaryIncrement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salaryIncrement = SalaryIncrement::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown
        $salaryGrades = \App\Models\SalaryGrade::pluck('grade', 'id');

        if (empty($salaryIncrement)) {
            return response()->json(['error' => 'Salary Increment not found'], 404);
        }

        return response()->json(['salaryIncrements' => $salaryIncrement, 'users' => $users, 'salaryGrades' => $salaryGrades]);
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
        $salaryIncrement = SalaryIncrement::find($id);

        if (empty($salaryIncrement)) {
            return response()->json(['error' => 'Salary Increment not found'], 404);
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/salary_increments';
            $customName = 'salary-increment-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }

        $salaryIncrement->update($input);

        // Update user's salary structure
        $user = User::find($salaryIncrement->user_id);
        if ($user) {
            $user->basic_salary = $input['new_salary'];
            $user->salary_grade_id = $input['new_grade_id'];
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Salary Increment updated successfully.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salaryIncrement = SalaryIncrement::find($id);

        if (empty($salaryIncrement)) {
            return response()->json(['error' => 'Salary Increment not found'], 404);
        }

        // Delete associated document if exists
        if ($salaryIncrement->document && file_exists(public_path($salaryIncrement->document))) {
            unlink(public_path($salaryIncrement->document));
        }

        $salaryIncrement->delete();

        return response()->json(['success' => true, 'message' => 'Salary Increment deleted successfully.'], 200);
    }

    public function list($user_id)
    {
        $users = SalaryIncrement::with(['oldGrade', 'newGrade'])->where('user_id', $user_id)->get();
        return response()->json(['salaryIncrements' => $users], 200);
    }
}
