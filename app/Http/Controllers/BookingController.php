<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use App\Models\Employee;
use Auth;
use Carbon\Carbon;


class BookingController extends Controller
{
    public function index()
    {
        // dd(Auth()->user()->id);
        $organization = Organization::all()->first(); //
        $bookings = Booking::where('organization_id', $organization->organization_id)
            ->orderBy('id', 'DESC')
            ->paginate(8);
        $users = User::where([['status', 1], ['role', 3]])->get();
        // $services = Service::where([['organization_id', $organization->organization_id], ['status', 1]])->get();
        $emps = Employee::where([['status', 1], ['organization_id', $organization->organization_id]])->get();

        return view('admin.pages.booking', compact('bookings', 'users', 'emps'));
    }

    public function create()
    {
        $organization_id = Organization::all()->first()->organization_id; //
        // $services = Service::where('organization_id', $organization_id)->get();
        $users = User::where([['status', 1], ['role', 3]])->get();
        dd($users);
        $emps = Employee::where([['status', 1], ['organization_id', $organization_id]])->get();

        return view('admin.booking.create', compact('users', 'emps'));
    }

    public function timeslot(Request $request)
    {
        $organization = Organization::all()->first(); //

        $master = array();
        $day = strtolower(Carbon::parse($request->date)->format('l'));
        $organization = Organization::find($organization->organization_id)->$day;
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

    public function selectemployee(Request $request)
    {
        $organization = Organization::all()->first(); //

        $emp_array = array();
        $emps_all = Employee::where([['organization_id', $organization->organization_id], ['status', 1]])->get();
        // $book_service = $request->service;

        // $duration = Service::whereIn('service_id', $book_service)->sum('time') - 1;
        // $duration = 1;
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
        $emps_final_1 = Employee::whereIn('emp_id', $new)->get();
        if (count($emps_final_1) > 0) {
            return response()->json(['msg' => 'Employees', 'data' => $emps_final_1, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'No employee available at this time', 'success' => false], 200);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'bail|required',
            'user_id' => 'bail|required',
            'date' => 'bail|required',
            'start_time' => 'bail|required',
            'emp_id' => 'bail|required',
        ]);

        $organization = Organization::all()->first(); //
        $booking = new Booking();

        // $services =  str_replace('"', '', json_encode($request->service_id));
        // $booking->service_id = $services;
        $duration = 60;

        $start_time = new Carbon($request['date'] . ' ' . $request['start_time']);
        $booking->end_time = $start_time->addMinutes($duration)->format('h:i A');
        $booking->organization_id = $organization->organization_id;
        $booking->emp_id = $request->emp_id;
        $booking->start_time = $request->start_time;
        $booking->date = $request->date;
        $booking->booking_status = "Approved";
        $booking->user_id = $request->user_id;
        $booking->booking_id = $request->booking_id;

        $booking->save();


        return response()->json(['msg' => 'Booking successfully', 'data' => $booking, 'success' => true], 200);
    }

    public function show($id)
    {
        $data['booking'] = Booking::with('user')->find($id);
        return response()->json(['success' => true, 'data' => $data, 'msg' => 'Appointment show'], 200);
    }

    public function changeStatus(Request $request)
    {
        $booking = Booking::find($request->bookingId);
        $booking->booking_status = $request->status;
        
        $booking->save();
    }
}
