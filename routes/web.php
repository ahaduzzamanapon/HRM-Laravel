<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\SalesController;


include 'web_builder.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('index');
})->middleware('auth');



Route::resource('trainingDetails', App\Http\Controllers\TrainingDetailController::class);
Route::resource('jobExperiences', App\Http\Controllers\JobExperienceController::class);
Route::resource('educationalQualifications', App\Http\Controllers\EducationalQualificationController::class);
Route::resource('nomineeInformation', App\Http\Controllers\NomineeInformationController::class);
Route::resource('promotionDetails', App\Http\Controllers\PromotionDetailController::class);
Route::resource('salaryIncrements', App\Http\Controllers\SalaryIncrementController::class);
Route::resource('transferDetails', App\Http\Controllers\TransferDetailController::class);
Route::resource('personalDocuments', App\Http\Controllers\PersonalDocumentController::class);

Route::resource('allowanceSettings', App\Http\Controllers\AllowanceSettingController::class);





