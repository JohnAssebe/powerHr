
<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserDisplayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TherapistApiController;
use App\Models\Booking;
use App\Models\User;
use App\Models\Employee;
use App\Models\RequestStatement;
use App\Models\Prescription;
use Illuminate\Support\Collection;

Auth::routes();
// Route::get('/join', function () {
//     return View('room');
// });

Route::get('/', function () {
    return redirect('admin/login');
});
Route::get('/admin/logout', function () {
    return redirect('admin/login');
});
Route::get('/login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/admin/login', [UserController::class, 'admin']);
Route::post('/admin/login/verify', [UserController::class, 'login_web']);
Route::get('/admin/organization/create', [OrganizationController::class, 'create']);
Route::post('/admin/organization/store', [OrganizationController::class, 'store']);

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Route::prefix('admin')->group(function () {
    Route::get('admin/logout', [UserController::class, 'admin_logout']);
    // Dahboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    //Employees
    Route::resource('/employee', EmployeeController::class);
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::get('/employee/{id}', [EmployeeController::class, 'show']);
    Route::get('/employee/create', [EmployeeController::class, 'create']);
    Route::post('/employee/store', [EmployeeController::class, 'store']);
    Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit']);
    Route::post('/employee/update/{id}', [EmployeeController::class, 'update']);
    Route::post('/employee/hideEmp', [EmployeeController::class, 'hideEmp']);
    Route::get('/employee/delete/{id}', [EmployeeController::class, 'destroy']);
    //booking
    // Route::resource('/booking', 'admin\BookingController');
    Route::get('/booking', [BookingController::class, 'index']);
    // Route::get('/booking/{id}', [BookingController::class, 'show']);
    Route::get('/modal/getdata/{id}', [BookingController::class, 'show']);
    Route::get('/booking/create', [BookingController::class, 'create']);
    Route::post('/booking/store', [BookingController::class, 'store']);
    Route::post('/booking/changestatus', [BookingController::class, 'changeStatus']);
    Route::post('/booking/changepaymentstatus', [BookingController::class, 'changePaymentStatus']);
    Route::post('/booking/paymentcount', [BookingController::class, 'paymentcount']);
    Route::post('/booking/timeslot', [BookingController::class, 'timeslot']);
    Route::post('/booking/selectemployee', [BookingController::class, 'selectemployee']);

    // //users admin
    Route::resource('/users', UserDisplayController::class);
    Route::get('/users/{id}', [UserDisplayController::class, 'show']);
    Route::post('/users/filter', [UserDisplayController::class, 'user_index_filter']);
    Route::get('/users/create', [UserDisplayController::class, 'create']);
    Route::post('/users/store', [UserDisplayController::class, 'store']);
    Route::get('/users/delete/{id}', [UserDisplayController::class, 'destroy']);
    Route::post('/users/hideUser', [UserDisplayController::class, 'hideUser']);
    //Organization
    Route::get('/organization', [OrganizationController::class, 'index']);
    Route::get('/organization/create', [OrganizationController::class, 'create']);
    Route::get('/organization/edit', [OrganizationController::class, 'edit']);
    Route::post('/organization/update/{id}', [OrganizationController::class, 'update']);
    Route::post('/organization/hideOrganization', [OrganizationController::class, 'hideOrganization']);
    Route::post('/organization/dayoff', [OrganizationController::class, 'organizationDayOff']);
});

Route::get('fetchreported', [TherapistApiController::class, 'fetchReportedPatients']);
Route::get('remove/{id}', [TherapistApiController::class, 'removeReportedPatient']);



Route::get("testingemployeedata", function () {
    // $ids = [2,4];
    // $employee = Employee::where([["organization_id", 1], ["profession", "psychiatrist"]],)->with('user')->get();
    // // $employee = $employee->whereIn('emp_id', $ids);
    // dd($employee);
    // return $employee;


});

Route::get("testFetch", function () {
    // $user = User::where('role', 3)->pluck('id');
    // dd($user);
    // $ids = 2;
    // $arr = [];
    
    // $patients = Booking::with('user')->where('emp_id', $ids);
    // $patients = Booking::with('user')->where('emp_id', $ids);
    // info($patients);
    // foreach ($patients as $patient) {
        // var_dump($patient->user->full_name);
    // }
    // dd($patients);


    $new_requests = RequestStatement::where('resolved', 0)->get()->map( function ($value){
            $value->with('user');
    });
    if (count($new_requests) > 0) {
        return response()->json(["status" => true, "data" => $new_requests, "msg" => "Request Statements retrieved Successfully"]);
    }

});



Route::get("testprescription", function () {
    $ids = [2, 4];
    $prescription = Prescription::where('created_at', '=',  '2021-10-04 21:10:38');
    // $employee = $employee->whereIn('emp_id', $ids);
    // dd($prescription->toSql());
    return $prescription->get();

    // $patients = Booking::all();
    // $newA = $patients->map(function($value){
    //     return $value->userDetails->pluck('full_name', 'email', 'role');
    // });

    // dd(gettype($newA));

});


