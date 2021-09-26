<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Booking;
use App\Models\Employee;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentUserApi extends Controller
{

    // time slot
    public function timeSlot(Request $request)
    {
        $request->validate([
            'date' => 'bail|required',
        ]);
        $id = Organization::first()->organization_id;

        $master = array();
        $day = strtolower(Carbon::parse($request->date)->format('l'));
        $organization = Organization::find($id)->$day;
        $start_time = new Carbon($request['date'] . ' ' . $organization['open']);

        $end_time = new Carbon($request['date'] . ' ' . $organization['close']);
        $diff_in_minutes = $start_time->diffInMinutes($end_time);
        for ($i = 0; $i <= $diff_in_minutes; $i += 30) {
            if ($start_time >= $end_time) {
                break;
            } else {
                $temp['start_time'] = $start_time->format('h:i A');
                $temp['end_time'] = $start_time->addMinutes('30')->format('h:i A');
                if ($request->date == date('Y-m-d')) {
                    if (strtotime(date("h:i A")) < strtotime($temp['start_time'])) {
                        array_push($master, $temp);
                    }
                } else {
                    array_push($master, $temp);
                }
            }
        }

        if (count($master) == 0) {
            return response()->json(['msg' => 'Day off', 'success' => false], 200);
        } else {
            return response()->json(['msg' => 'Time slots', 'data' => $master, 'success' => true], 200);
        }
    }

    // select emp
    public function selectEmp(Request $request)
    {
        dd($request);
        $request->validate([
            'start_time' => 'bail|required',
            'date' => 'bail|required',
        ]);

        $organization_id = Organization::first()->organization_id;
        $emp_array = array(); // need to use the following code to fill this with employees

        // gets employees and tries to get all the services by that employee

        $emps_all = Employee::where([['organization_id', $organization_id], ['status', 1]])->get();
        // $book_service = json_decode($request->service);

        // $duration = Service::whereIn('service_id', $book_service)->sum('time') - 1;
        foreach ($emps_all as $emp) {
            // $emp_service = json_decode($emp->service_id);
            // foreach ($book_service as $ser) {
            //     if (in_array($ser, $emp_service)) {
            array_push($emp_array, $emp->emp_id);
            // }
            // }
        }
        $master =  array();
        $emps = Employee::whereIn('emp_id', $emp_array)->get();
        $time = new Carbon($request['date'] . ' ' . $request['start_time']);
        $day = strtolower(Carbon::parse($request->date)->format('l'));
        $date = $request->date;
        foreach ($emps as $emp) {
            $employee = $emp->$day;

            $start_time = new Carbon($request['date'] . ' ' . $employee['open']);
            $end_time = new Carbon($request['date'] . ' ' . $employee['close']);

            if ($time->between($start_time, $end_time)) {
                array_push($master, $emp);
            }
        }

        $emps_final = array();
        foreach ($master as $emp) {
            $booking = Booking::where([['emp_id', $emp->emp_id], ['date', $date], ['start_time', $request['start_time']], ['booking_status', 'Approved']])
                ->orWhere([['emp_id', $emp->emp_id], ['date', $date], ['start_time', $request['start_time']], ['booking_status', 'Pending']])
                ->get();
            if (count($booking) == 0) {
                array_push($emps_final, $emp);
            }
        }
        $new = array();
        foreach ($emps_final as $emp) {
            array_push($new, $emp->emp_id);
        }

        $emps_final_1 = Employee::whereIn('emp_id', '1')
            ->get(['emp_id', 'organization_id'])->makeHidden(['organization', 'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        $name = $emps_final_1->user()->first();

        $emps_final_1->push('name', $name);

        if (count($emps_final_1) > 0) {
            return response()->json(['msg' => 'Employees', 'data' => $emps_final_1, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'No employee available at this time', 'success' => false], 200);
        }
    }

    // booking / notification
    public function booking(Request $request)
    {
        dd($request);
        $request->validate([
            'emp_id' => 'bail|required',
            'date' => 'bail|required',
            'start_time' => 'bail|required',
        ]);

        $organization_id = Organization::first()->organization_id;
        $booking = new Booking();
        $start_time = new Carbon($request['date'] . ' ' . $request['start_time']);
        $booking->end_time = $start_time->addMinutes(60)->format('h:i A');
        $booking->organization_id = $organization_id;
        $booking->emp_id = $request->emp_id;
        $booking->start_time = $request->start_time;
        $booking->date = $request->date;

        $booking->user_id = Auth()->user()->id;
        $bid = rand(10000, 99999);
        $booking->booking_id = '#' . $bid;

        $booking->booking_status = 'Pending';
        $booking->save();

        return response()->json(['msg' => 'Booking successfully', 'success' => true], 200);
    }

    // All  Appointment
    public function showAppointment()
    {
        $master = array();
        $master['completed'] = Booking::where([['user_id', Auth::user()->id], ['booking_status', 'Completed']])
            ->with(['employee:emp_id,name,organization_id'])
            ->orderBy('id', 'DESC')->get()
            ->makeHidden(['userDetails', 'empDetails', 'organization_id', 'created_at', 'updated_at', 'user_id']);
        foreach ($master['completed'] as $item) {
            $item->employee->makeHidden(['organization',  'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        }

        $master['cancel'] = Booking::where([['user_id', Auth::user()->id], ['booking_status', 'cancel']])
            ->with(['employee:emp_id,name,organization_id'])
            ->orderBy('id', 'DESC')->get()
            ->makeHidden(['userDetails', 'empDetails', 'organization_id', 'created_at', 'updated_at', 'user_id']);
        foreach ($master['cancel'] as $item) {
            $item->employee->makeHidden(['organization', 'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        }

        $master['upcoming_order'] = Booking::where([['user_id', Auth::user()->id], ['booking_status', 'Pending']])
            ->orWhere([['user_id', Auth::user()->id], ['booking_status', 'Approved']])
            ->with(['employee:emp_id,name,organization_id'])
            ->orderBy('id', 'DESC')->get()
            ->makeHidden(['userDetails', 'empDetails', 'organization_id', 'created_at', 'updated_at', 'user_id']);
        foreach ($master['upcoming_order'] as $item) {
            $item->employee->makeHidden(['organization',  'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        }

        return response()->json(['msg' => 'User Appointments', 'data' => $master, 'success' => true], 200);
    }

    // Single Appointment
    public function singleAppointment($id)
    {
        $booking = Booking::where('id', $id)
            ->with(['employee:emp_id,name,organization_id'])
            ->find($id)
            ->makeHidden(['userDetails', 'empDetails', 'organization_id', 'created_at', 'updated_at', 'user_id']);
        $booking->employee->makeHidden(['organization', 'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);
        return response()->json(['msg' => 'Single Appointments', 'data' => $booking, 'success' => true], 200);
    }

    // Cancel Appointment
    public function cancelAppointment($id)
    {
        $booking = Booking::find($id);
        $booking->booking_status = "Cancel";
        $booking->save();


        return response()->json(['msg' => 'Appointment Cancel', 'success' => true], 200);
    }
}
