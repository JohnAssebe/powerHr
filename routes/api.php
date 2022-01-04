<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DisorderController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentUserApi;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ApiEmployeeController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\TherapistApiController;
use App\Http\Controllers\RequestStatementController;

Route::post('writeprescription', [PrescriptionController::class, 'store']);
Route::post('updateprescription', [PrescriptionController::class, 'update']);
Route::get('showprescription/{patient_id}', [PrescriptionController::class, 'show']);
Route::get('deleteprescription/{prescription_id}', [PrescriptionController::class, 'destroy']);

Route::get('report/{id}', [TherapistApiController::class, 'report']);
Route::get('fetchpatients', [TherapistApiController::class, 'fetchPatients']);
// Route::get('timeslottherapist', [RequestStatementController::class, 'timeSlotTherapist']);

// Route::post('timeslottherapist', [RequestStatementController::class, 'timeSlotTherapist']);
// Route::get('fetchpatients', [TherapistApiController::class, 'fetchPatients']);
//protected routes
Route::post('booking', [AppointmentUserApi::class, 'booking']);



Route::post('bookrequest', [RequestStatementController::class, 'bookRequest']);
Route::post('newrequest', [RequestStatementController::class, 'createNewRequest']);
Route::get('fetchrequests', [RequestStatementController::class, 'fetchNewRequests']);


Route::post('update', [UserController::class, 'update_account']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('createMeeting', [MeetingController::class, 'createAndJoinMeeting']);

    // Route::get('all_users', [UserController::class, 'all_account']);
    Route::get('delete/{id}', [UserController::class, 'delete_account']);
    // Route::get('search/{user_name}', [UserController::class, 'search_account']);
    Route::post('logout', [UserController::class, 'logout']);
    // Route::post('upload', [UserController::class, 'upload_pic']);
    // Route::get('survey', [SurveyController::class, 'questions_and_associated_answers']);
    // Route::post('therapist_speciality', [TherapistController::class, 'therapist_disorder_speciality_insertion']);
    Route::post('add_therapist', [TherapistController::class, 'register_profile']);
    //Admin Routes
    Route::post('register_admin', [AdminController::class, 'register_admin']);
    Route::get('delete_admin/{id}', [AdminController::class, 'remove_admin']);
    Route::post('update_admin/{id}', [AdminController::class, 'update_profile']);
    Route::get('all_admins', [AdminController::class, 'all_admins']);
    Route::get('all_patients', [AdminController::class, 'all_patients']);
    Route::get('remove_patient/{id}', [AdminController::class, 'remove_patient']);
    Route::get('search_patient/{name}', [AdminController::class, 'find_patient']);
    Route::get('all_approved_therapist', [AdminController::class, 'all_therapist']);
    Route::get('all_unapproved_therapist', [AdminController::class, 'therapist_unapproved']);
    Route::get('delete_therapist/{id}', [AdminController::class, 'remove_therapist']);
    Route::get('search_therapist/{name}', [AdminController::class, 'search_therapist']);
    Route::post('approve_therapist/{id}', [AdminController::class, 'approve_therapist']);


    //Patient Routes
    Route::post('register_patient', [PatientController::class, 'register_patient']);
    Route::get('all_patient', [PatientController::class, 'all_patients']);
    Route::get('patient/{id}', [PatientController::class, 'patient']);
    Route::post('update_patient/{id}', [PatientController::class, 'update_profile']);
    Route::get('delete_patient/{id}', [PatientController::class, 'delete_patient']);
    Route::post('select_therapist/{id}', [PatientController::class, 'select_therapist']);
    Route::get('selected_therapist', [PatientController::class, 'selected_therapist']);

});

Route::view('/', 'welcome');
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
// Route::post('is_valid', [UserController::class, 'is_user_input_valid']);

// Therapist Authentication
Route::post('registerEmployee', [ApiEmployeeController::class, 'register']);
Route::post('loginEmployee', [ApiEmployeeController::class, 'login']);
Route::post('logoutEmployee', [ApiEmployeeController::class, 'logout']);

//Survey Routes
Route::post('add_survey_question', [SurveyController::class, 'add_survey_question']);
Route::post('add_survey_answer/{id}', [SurveyController::class, 'add_survey_answer']);
Route::get('get_answer_from_question/{id}', [SurveyController::class, 'get_answers_from_question']);
Route::post('update_survey_question/{id}', [SurveyController::class, 'update_survey_question']);
Route::post('update_survey_answer/{id}', [SurveyController::class, 'update_survey_answer']);
Route::post('delete_survey/{id}', [SurveyController::class, 'delete_survey']);

//Disorder Routes
Route::post('add_disorder', [DisorderController::class, 'insert_disorder']);
Route::get('delete_disorder/{id}', [DisorderController::class, 'delete_disorder']);
Route::get('disorder_therapist', [DisorderController::class, 'disorder_related_therapists']);




//Therapist Routes
Route::get('therapist/{id}', [TherapistController::class, 'fetch_profile']);
Route::get('all_therapist', [TherapistController::class, 'all_therpist']);
Route::get('all_therapist_specialization', [TherapistController::class, 'all_therapists_with_disorder_specialization']);
Route::get('therapist_specialization/{id}', [TherapistController::class, 'therapist_with_specialization']);
Route::post('therapist_update/{id}', [TherapistController::class, 'update_therapist_profile']);
// Route::get('user_therapist',[TherapistController::class,'therpist_user']);


Route::get('/organization', [AppointmentUserApi::class, 'singleOrganization']);
Route::get('/categories', [AppointmentUserApi::class ,'categories']);

Route::post('/timeslot', [AppointmentUserApi::class ,'timeSlot']);
Route::post('/selectemp', [AppointmentUserApi::class, 'selectEmp']);

Route::middleware('auth:api')->group(function () {
    Route::get('/appointment', 'api\UserApiController@showAppointment');
    Route::get('/appointment/{id}', 'api\UserApiController@singleAppointment');
    Route::get('/appointment/cancel/{id}', 'api\UserApiController@cancelAppointment');
});

Route::get('echo', function(){
    echo "jello";
});
