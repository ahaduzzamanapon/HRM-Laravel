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
        $input = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $folder = 'documents/promotion';
            $customName = 'promotion-document-'.time();
            $input['document'] = uploadFile($file, $folder, $customName);
        }

        PromotionDetail::create($input);

        Flash::success('Promotion Detail saved successfully.');
        return redirect(route('promotionDetails.index'));
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
            Flash::error('Promotion Detail not found');
            return redirect(route('promotionDetails.index'));
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

        if (empty($promotionDetail)) {
            Flash::error('Promotion Detail not found');
            return redirect(route('promotionDetails.index'));
        }

        return view('promotion_details.edit')->with(['promotionDetail' => $promotionDetail, 'users' => $users]);
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

        Flash::success('Promotion Detail updated successfully.');
        return redirect(route('promotionDetails.index'));
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

        Flash::success('Promotion Detail deleted successfully.');
        return redirect(route('promotionDetails.index'));
    }
}
