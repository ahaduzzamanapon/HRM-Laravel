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

        // Save Training Details
        if ($request->has('training_details') && is_array($request->training_details)) {
            foreach ($request->training_details as $trainingDetailData) {
                $users->trainingDetails()->create($trainingDetailData);
            }
        }

        // Save Job Experiences
        if ($request->has('job_experiences') && is_array($request->job_experiences)) {
            foreach ($request->job_experiences as $jobExperienceData) {
                $users->jobExperiences()->create($jobExperienceData);
            }
        }

        // Save Educational Qualifications
        if ($request->has('educational_qualifications') && is_array($request->educational_qualifications)) {
            foreach ($request->educational_qualifications as $educationalQualificationData) {
                $users->educationalQualifications()->create($educationalQualificationData);
            }
        }

        // Save Nominee Information
        if ($request->has('nominee_information') && is_array($request->nominee_information)) {
            foreach ($request->nominee_information as $nomineeInformationData) {
                $users->nomineeInformation()->create($nomineeInformationData);
            }
        }

        // Save Promotion Details
        if ($request->has('promotion_details') && is_array($request->promotion_details)) {
            foreach ($request->promotion_details as $promotionDetailData) {
                $users->promotionDetails()->create($promotionDetailData);
            }
        }

        // Save Salary Increments
        if ($request->has('salary_increments') && is_array($request->salary_increments)) {
            foreach ($request->salary_increments as $salaryIncrementData) {
                $users->salaryIncrements()->create($salaryIncrementData);
            }
        }

        // Save Transfer Details
        if ($request->has('transfer_details') && is_array($request->transfer_details)) {
            foreach ($request->transfer_details as $transferDetailData) {
                $users->transferDetails()->create($transferDetailData);
            }
        }

        // Save Personal Documents
        if ($request->has('personal_documents') && is_array($request->personal_documents)) {
            foreach ($request->personal_documents as $personalDocumentData) {
                $users->personalDocuments()->create($personalDocumentData);
            }
        }

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }


    public function show($id)
    {
        /** @var User $users */
        $users = User::with([
            'trainingDetails',
            'jobExperiences',
            'educationalQualifications',
            'nomineeInformation',
            'promotionDetails',
            'salaryIncrements',
            'transferDetails',
            'personalDocuments'
        ])->find($id);

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('users', $users);
    }


    public function edit($id)
    {
        /** @var User $users */
        $users = User::with([
            'trainingDetails',
            'jobExperiences',
            'educationalQualifications',
            'nomineeInformation',
            'promotionDetails',
            'salaryIncrements',
            'transferDetails',
            'personalDocuments'
        ])->find($id);

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

        // Update or Create Training Details
        if ($request->has('training_details') && is_array($request->training_details)) {
            foreach ($request->training_details as $trainingDetailData) {
                if (isset($trainingDetailData['id']) && !empty($trainingDetailData['id'])) {
                    // Update existing record
                    $trainingDetail = $users->trainingDetails()->find($trainingDetailData['id']);
                    if ($trainingDetail) {
                        $trainingDetail->update($trainingDetailData);
                    }
                } else {
                    // Create new record
                    $users->trainingDetails()->create($trainingDetailData);
                }
            }
        }

        // Update or Create Job Experiences
        if ($request->has('job_experiences') && is_array($request->job_experiences)) {
            foreach ($request->job_experiences as $jobExperienceData) {
                if (isset($jobExperienceData['id']) && !empty($jobExperienceData['id'])) {
                    // Update existing record
                    $jobExperience = $users->jobExperiences()->find($jobExperienceData['id']);
                    if ($jobExperience) {
                        $jobExperience->update($jobExperienceData);
                    }
                } else {
                    // Create new record
                    $users->jobExperiences()->create($jobExperienceData);
                }
            }
        }

        // Update or Create Educational Qualifications
        if ($request->has('educational_qualifications') && is_array($request->educational_qualifications)) {
            foreach ($request->educational_qualifications as $educationalQualificationData) {
                if (isset($educationalQualificationData['id']) && !empty($educationalQualificationData['id'])) {
                    // Update existing record
                    $educationalQualification = $users->educationalQualifications()->find($educationalQualificationData['id']);
                    if ($educationalQualification) {
                        $educationalQualification->update($educationalQualificationData);
                    }
                } else {
                    // Create new record
                    $users->educationalQualifications()->create($educationalQualificationData);
                }
            }
        }

        // Update or Create Nominee Information
        if ($request->has('nominee_information') && is_array($request->nominee_information)) {
            foreach ($request->nominee_information as $nomineeInformationData) {
                if (isset($nomineeInformationData['id']) && !empty($nomineeInformationData['id'])) {
                    // Update existing record
                    $nomineeInformation = $users->nomineeInformation()->find($nomineeInformationData['id']);
                    if ($nomineeInformation) {
                        $nomineeInformation->update($nomineeInformationData);
                    }
                } else {
                    // Create new record
                    $users->nomineeInformation()->create($nomineeInformationData);
                }
            }
        }

        // Update or Create Promotion Details
        if ($request->has('promotion_details') && is_array($request->promotion_details)) {
            foreach ($request->promotion_details as $promotionDetailData) {
                if (isset($promotionDetailData['id']) && !empty($promotionDetailData['id'])) {
                    // Update existing record
                    $promotionDetail = $users->promotionDetails()->find($promotionDetailData['id']);
                    if ($promotionDetail) {
                        $promotionDetail->update($promotionDetailData);
                    }
                } else {
                    // Create new record
                    $users->promotionDetails()->create($promotionDetailData);
                }
            }
        }

        // Update or Create Salary Increments
        if ($request->has('salary_increments') && is_array($request->salary_increments)) {
            foreach ($request->salary_increments as $salaryIncrementData) {
                if (isset($salaryIncrementData['id']) && !empty($salaryIncrementData['id'])) {
                    // Update existing record
                    $salaryIncrement = $users->salaryIncrements()->find($salaryIncrementData['id']);
                    if ($salaryIncrement) {
                        $salaryIncrement->update($salaryIncrementData);
                    }
                } else {
                    // Create new record
                    $users->salaryIncrements()->create($salaryIncrementData);
                }
            }
        }

        // Update or Create Transfer Details
        if ($request->has('transfer_details') && is_array($request->transfer_details)) {
            foreach ($request->transfer_details as $transferDetailData) {
                if (isset($transferDetailData['id']) && !empty($transferDetailData['id'])) {
                    // Update existing record
                    $transferDetail = $users->transferDetails()->find($transferDetailData['id']);
                    if ($transferDetail) {
                        $transferDetail->update($transferDetailData);
                    }
                } else {
                    // Create new record
                    $users->transferDetails()->create($transferDetailData);
                }
            }
        }

        // Update or Create Personal Documents
        if ($request->has('personal_documents') && is_array($request->personal_documents)) {
            foreach ($request->personal_documents as $personalDocumentData) {
                if (isset($personalDocumentData['id']) && !empty($personalDocumentData['id'])) {
                    // Update existing record
                    $personalDocument = $users->personalDocuments()->find($personalDocumentData['id']);
                    if ($personalDocument) {
                        $personalDocument->update($personalDocumentData);
                    }
                } else {
                    // Create new record
                    $users->personalDocuments()->create($personalDocumentData);
                }
            }
        }

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
