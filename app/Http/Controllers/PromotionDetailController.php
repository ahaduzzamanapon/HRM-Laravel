<?php

namespace App\Http\Controllers;

use App\Models\PromotionDetail;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Flash;
use Response;

class PromotionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotionDetails = PromotionDetail::paginate(10);
        return view('promotion_details.index')->with('promotionDetails', $promotionDetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::pluck('name', 'id'); // Get users for dropdown
        return view('promotion_details.create')->with('users', $users);
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
            $folder = 'documents/promotion';
            $customName = 'promotion-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        PromotionDetail::create($input);

        // Update user's salary structure
        $user->salary_grade_id = $input['new_grade_id'];
        $user->save();

        return response()->json(['success' => true, 'message' => 'Promotion Detail saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promotionDetail = PromotionDetail::find($id);

        if (empty($promotionDetail)) {
            return response()->json(['success' => false, 'message' => 'Promotion Detail not found']);
        }

        return view('promotion_details.show')->with('promotionDetail', $promotionDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotionDetail = PromotionDetail::find($id);
        $users = User::pluck('name', 'id'); // Get users for dropdown
        $salaryGrades = \App\Models\SalaryGrade::pluck('grade', 'id');

        if (empty($promotionDetail)) {
            Flash::error('Promotion Detail not found');
            return redirect(route('promotionDetails.index'));
        }

        return response()->json(['promotionDetail' => $promotionDetail, 'users' => $users, 'salaryGrades' => $salaryGrades]);
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
        $promotionDetail = PromotionDetail::find($id);

        if (empty($promotionDetail)) {
            Flash::error('Promotion Detail not found');
            return redirect(route('promotionDetails.index'));
        }

        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/promotion';
            $customName = 'promotion-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        } else {
            unset($input['document']); // Don't update document if not provided
        }

        $promotionDetail->update($input);

        // Update user's salary structure
        $user = User::find($promotionDetail->user_id);
        if ($user) {
            $user->salary_grade_id = $input['new_grade_id'];
            $user->save();
        }

        return response()->json(['success' => true, 'message' => 'Promotion Detail updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promotionDetail = PromotionDetail::find($id);

        if (empty($promotionDetail)) {
            Flash::error('Promotion Detail not found');
            return redirect(route('promotionDetails.index'));
        }

        // Delete associated document if exists
        if ($promotionDetail->document && file_exists(public_path($promotionDetail->document))) {
            unlink(public_path($promotionDetail->document));
        }

        $promotionDetail->delete();

        return response()->json(['success' => true, 'message' => 'Promotion Detail deleted successfully.']);
    }
    public function list($user_id)
    {
        $users = PromotionDetail::with(['oldGrade', 'newGrade'])->where('user_id', $user_id)->get();
        return response()->json(['success' => true, 'promotionDetail' => $users]);
    }
}
