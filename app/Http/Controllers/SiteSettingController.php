<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSiteSettingRequest;
use App\Http\Requests\UpdateSiteSettingRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Flash;
use Response;
use File;

class SiteSettingController extends AppBaseController
{
    /**
     * Display a listing of the SiteSetting.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var SiteSetting $siteSettings */
        $siteSettings = SiteSetting::all();
        return view('site_settings.index')
            ->with('siteSettings', $siteSettings);
    }

    /**
     * Show the form for creating a new SiteSetting.
     *
     * @return Response
     */
    public function create()
    {
        return view('site_settings.create');
    }

    /**
     * Store a newly created SiteSetting in storage.
     *
     * @param CreateSiteSettingRequest $request
     *
     * @return Response
     */
    public function store(CreateSiteSettingRequest $request)
    {
        $input = $request->all();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $folder = 'images/site';
            $customName = 'item-'.time();
            $input['logo'] = uploadFile($file, $folder, $customName);
        }

        /** @var SiteSetting $siteSetting */
        $siteSetting = SiteSetting::create($input);

        Flash::success('Site Settings saved successfully.');

        return redirect(route('siteSettings.index'));
    }

    /**
     * Display the specified SiteSetting.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SiteSetting $siteSetting */
        $siteSetting = SiteSetting::find($id);

        if (empty($siteSetting)) {
            Flash::error('Site Settings not found');

            return redirect(route('siteSettings.index'));
        }

        return view('site_settings.show')->with('siteSetting', $siteSetting);
    }

    /**
     * Show the form for editing the specified SiteSetting.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var SiteSetting $siteSetting */
        $siteSetting = SiteSetting::find($id);

        if (empty($siteSetting)) {
            Flash::error('Site Settings not found');

            return redirect(route('siteSettings.index'));
        }

        return view('site_settings.edit')->with('siteSetting', $siteSetting);
    }

    /**
     * Update the specified SiteSetting in storage.
     *
     * @param int $id
     * @param UpdateSiteSettingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSiteSettingRequest $request)
    {
        /** @var SiteSetting $siteSetting */
        $siteSetting = SiteSetting::find($id);

        if (empty($siteSetting)) {
            Flash::error('Site Settings not found');

            return redirect(route('siteSettings.index'));
        }
        $input = $request->all();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $folder = 'images/site';
            $customName = 'item-'.time();
            $input['logo'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['logo']);
        }



        $siteSetting->fill($input);
        $siteSetting->save();

        Flash::success('Site Settings updated successfully.');

        return redirect(route('siteSettings.index'));
    }

    /**
     * Remove the specified SiteSetting from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SiteSetting $siteSetting */
        $siteSetting = SiteSetting::find($id);



        if (empty($siteSetting)) {
            Flash::error('Site Settings not found');

            return redirect(route('siteSettings.index'));
        }





        $siteSetting->delete();

        Flash::success('Site Settings deleted successfully.');

        return redirect(route('siteSettings.index'));
    }
}
