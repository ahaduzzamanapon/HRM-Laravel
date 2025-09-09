<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNoticeRequest;
use App\Http\Requests\UpdateNoticeRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Notice;
use Illuminate\Http\Request;
use Flash;
use Response;

class NoticeController extends AppBaseController
{
    /**
     * Display a listing of the Notice.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Notice $notices */
        $notices = Notice::paginate(10);

        return view('notices.index')
            ->with('notices', $notices);
    }

    /**
     * Show the form for creating a new Notice.
     *
     * @return Response
     */
    public function create()
    {
        return view('notices.create');
    }

    /**
     * Store a newly created Notice in storage.
     *
     * @param CreateNoticeRequest $request
     *
     * @return Response
     */
    public function store(CreateNoticeRequest $request)
    {
        $input = $request->all();

        /** @var Notice $notice */
        $notice = Notice::create($input);

        Flash::success('Notice saved successfully.');

        return redirect(route('notices.index'));
    }

    /**
     * Display the specified Notice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Notice $notice */
        $notice = Notice::find($id);

        if (empty($notice)) {
            Flash::error('Notice not found');

            return redirect(route('notices.index'));
        }

        return view('notices.show')->with('notice', $notice);
    }

    /**
     * Show the form for editing the specified Notice.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Notice $notice */
        $notice = Notice::find($id);

        if (empty($notice)) {
            Flash::error('Notice not found');

            return redirect(route('notices.index'));
        }

        return view('notices.edit')->with('notice', $notice);
    }

    /**
     * Update the specified Notice in storage.
     *
     * @param int $id
     * @param UpdateNoticeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNoticeRequest $request)
    {
        /** @var Notice $notice */
        $notice = Notice::find($id);

        if (empty($notice)) {
            Flash::error('Notice not found');

            return redirect(route('notices.index'));
        }

        $notice->fill($request->all());
        $notice->save();

        Flash::success('Notice updated successfully.');

        return redirect(route('notices.index'));
    }

    /**
     * Remove the specified Notice from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Notice $notice */
        $notice = Notice::find($id);

        if (empty($notice)) {
            Flash::error('Notice not found');

            return redirect(route('notices.index'));
        }

        $notice->delete();

        Flash::success('Notice deleted successfully.');

        return redirect(route('notices.index'));
    }
}
