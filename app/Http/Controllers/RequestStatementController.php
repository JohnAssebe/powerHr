<?php

namespace App\Http\Controllers;

use App\Models\RequestStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Organization;
use App\Models\Employee;
use App\Models\Booking;
use Exception;

class RequestStatementController extends Controller
{
    public function fetchNewRequests()
    {
        $new_requests = RequestStatement::where('resolved', 0)->get();
        if (count($new_requests) > 0) {
            return response()->json(["status" => true, "data" => $new_requests, "msg" => "Request Statements retrieved Successfully"]);
        } else {
            return response()->json(["status" => false, "msg" => "No new requests"]);
        }
    }

    public function createNewRequest(Request $request)
    {
        $requestStatement = new RequestStatement();
        $requestStatement->patient_id = $request->patient_id;
        $requestStatement->request_statement = $request->request_statement;
        $requestStatement->disorder = $request->disorder;
        $requestStatement->save();
        return response()->json(["status" => true, "msg" => "Request Statement Created Successfully"]);
    }

    public function timeSlotTherapist(Request $request)
    {
        $id = Organization::first()->organization_id;

        $master = array();
        $day = strtolower(Carbon::parse($request->date)->format('l'));
        // dd($day);
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

    public function bookRequest(Request $request)
    {
        $request->validate([
            'start_time' => 'bail|required',
            'date' => 'bail|required',
        ]);
        $employee_id = 2;
        $emp = Employee::find($employee_id);

        $master =  array();
        $time = new Carbon($request['date'] . ' ' . $request['start_time']);
        $day = strtolower(Carbon::parse($request->date)->format('l'));
        $date = $request->date;
        $employee = $emp->$day;

        $start_time = new Carbon($request['date'] . ' ' . $employee['open']);
        $end_time = new Carbon($request['date'] . ' ' . $employee['close']);

        if ($time->between($start_time, $end_time)) {
            array_push($master, $emp);
        }


        try {
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
            $emps_final_1 = Employee::whereIn('emp_id', $new)
                ->get(['emp_id', 'organization_id'])->makeHidden(['organization', 'organization_id', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']);


            if (count($emps_final_1) > 0) {
                $response = $this->booking($request, $emps_final_1->first()->emp_id);
                return response()->json(['msg' => 'Employees', 'data' => $response, 'success' => true], 200);
            } else {
                return response()->json(['msg' => 'No employee available at this time', 'success' => false], 400);
            }
        } catch (exception $e) {
            // dd($e);
            return response()->json(["data" => "Not Booked", "status" => false], 500);
        }
    }

    public function booking(Request $request, $emp_id)
    {

        // dd($request);
        $organization_id = Organization::first()->organization_id;
        $booking = new Booking();
        $start_time = new Carbon($request['date'] . ' ' . $request['start_time']);
        $booking->end_time = $start_time->addMinutes(60)->format('h:i A');
        $booking->organization_id = $organization_id;
        $booking->emp_id = $emp_id;
        $booking->start_time = $request->start_time;
        $booking->date = $request->date;
        // $booking->request = $request->request;
        $booking->session_type = $request->session_type;
        // $booking->user_id = Auth()->user()->id;

        $booking->user_id = $request->id;
        $bid = rand(10000, 99999);
        $booking->booking_id = '#' . $bid;

        $booking->booking_status = 'Pending';
        $booking->save();
        $requests = RequestStatement::find($request->request_id);
        $requests->resolved = 1;
        $requests->save();
        // dd($booking);

        return response()->json(['msg' => 'Booking successfully', 'success' => true], 200);
    }
}
