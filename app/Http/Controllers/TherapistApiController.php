<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Log\Logger;

class TherapistApiController extends Controller
{
    public function fetchPatients()
    {
        //this needs a complex querying
        $employee_id = 3;
        // $employee_id = $id;
        // Logger::info(Auth::user()->id);
        // $employee_id = Auth::user()->id;
        $new = array();
        $schedule = Booking::with('user')->where('emp_id', $employee_id)->get();
        // foreach($schedule as $sc){
        //     array_push($new, $sc->userDetails);
        // }
        return response()->json(["data" => $schedule, "status" => true, "msg" => "Current Patients List"], 200);
    }

    public function report($id)
    {
        $fetchedCurrentUser = User::find($id);
        $fetchedCurrentUser->reported = 1;
        $fetchedCurrentUser->save();
        return response()->json(["msg" => "patient successfully reported", "status" => true], 200);
    }

    public function fetchReportedPatients()
    {
        $patients = User::where('reported', 1)->get();
        return view('admin.users.reported', ["patients" => $patients]);
    }

    public function removeReportedPatient($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('fetchreported');
    }
}
