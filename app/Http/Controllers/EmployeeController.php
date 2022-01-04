<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Organization;
use App\Models\Booking;
use Carbon\Carbon;

class EmployeeController extends Controller
{
   
    public function index()
    {
        $organization = Organization::all()->first();
        $emps = Employee::where([['organization_id',$organization->organization_id]])
        ->orderBy('emp_id', 'DESC')
        ->paginate(10);
        
        return view('admin.pages.employee', compact('emps'));
    }

    public function create()
    {
        $organization = Organization::all()->first();
        // $services = Service::where(['organization_id', $organization->organization_id])->get();
        
        return view('admin/employee/create', compact('organization'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:employee',
            'phone' => 'bail|required|numeric',
        ]);

        $emp = new Employee();

        $organization = Organization::all()->first();
        $emp->organization_id = $organization->organization_id;
        $emp->name = $request->name;
        $emp->email = $request->email;
        $emp->phone = $request->phone;
        // $emp->service_id = json_encode($request->services);
  
        if($request->sunopen == null || $request->sunclose == null){
            $emp->sun = json_encode(array('open' => $request->sunopen,'close' => $request->sunclose));
        } else {
            $emp->sun = json_encode(array('open' => Carbon::parse($request->sunopen)->format('H:i'),'close' => Carbon::parse($request->sunclose)->format('H:i')));
        }
        
        if($request->monopen == null || $request->monclose == null){
            $emp->mon = json_encode(array('open' => $request->monopen,'close' => $request->monclose));
        } else {
            $emp->mon = json_encode(array('open' => Carbon::parse($request->monopen)->format('H:i'),'close' => Carbon::parse($request->monclose)->format('H:i')));
        }
  
        if($request->tueopen == null || $request->tueclose == null){
            $emp->tue = json_encode(array('open' => $request->tueopen,'close' => $request->tueclose));
        } else {
            $emp->tue = json_encode(array('open' => Carbon::parse($request->tueopen)->format('H:i'),'close' => Carbon::parse($request->tueclose)->format('H:i')));
        }

        if($request->wedopen == null || $request->wedclose == null){
            $emp->wed = json_encode(array('open' => $request->wedopen,'close' => $request->wedclose));
        } else {
            $emp->wed = json_encode(array('open' => Carbon::parse($request->wedopen)->format('H:i'),'close' => Carbon::parse($request->wedclose)->format('H:i')));
        }

        if($request->thuopen == null || $request->thuclose == null){
            $emp->thu = json_encode(array('open' => $request->thuopen,'close' => $request->thuclose));
        } else {
            $emp->thu = json_encode(array('open' => Carbon::parse($request->thuopen)->format('H:i'),'close' => Carbon::parse($request->thuclose)->format('H:i')));
        }

        if($request->friopen == null || $request->friclose == null){
            $emp->fri = json_encode(array('open' => $request->friopen,'close' => $request->friclose));
        } else {
            $emp->fri = json_encode(array('open' => Carbon::parse($request->friopen)->format('H:i'),'close' => Carbon::parse($request->friclose)->format('H:i')));
        }

        if($request->satopen == null || $request->satclose == null){
            $emp->sat = json_encode(array('open' => $request->satopen,'close' => $request->satclose));
        } else {
            $emp->sat = json_encode(array('open' => Carbon::parse($request->satopen)->format('H:i'),'close' => Carbon::parse($request->satclose)->format('H:i')));
        }

        $emp->save();
        return redirect('/admin/employee');
    }

    public function show($id)
    {
        $emp = Employee::find($id);
        $appointment = Booking::where('emp_id',$id)->get();
        $arr = array();
        foreach($appointment as $item)
        {
            array_push($arr,$item->user_id);
        }
        $count = array_count_values($arr);
        $client = array_keys($count);
        return view('admin.employee.show', compact('emp','appointment','client'));
    }

    public function edit($id)
    {
        $emp = Employee::find($id);
        $organization = Organization::all()->first();
        // $services = Service::where(['organization_id', $organization->organization_id])->get();

        $appointment = Booking::where('emp_id',$id)->get();
        $arr = array();
        foreach($appointment as $item)
        {
            array_push($arr,$item->user_id);
        }
        $count = array_count_values($arr);
        $client = array_keys($count);

        return view('admin.employee.edit', compact('emp', 'organization','appointment','client'));
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'bail|required',
        //     'email' => 'bail|required|email',
        //     'phone' => 'bail|required|numeric',
        // ]);

        $emp = Employee::find($id);
        
        
        // $emp->name = $request->name;
        // $emp->email = $request->email;
        // $emp->phone = $request->phone;

        
        if($request->sunopen == null || $request->sunclose == null){
            $emp->sun = json_encode(array('open' => $request->sunopen,'close' => $request->sunclose));
        } else {
            $emp->sun = json_encode(array('open' => Carbon::parse($request->sunopen)->format('H:i'),'close' => Carbon::parse($request->sunclose)->format('H:i')));
        }
        
        if($request->monopen == null || $request->monclose == null){
            $emp->mon = json_encode(array('open' => $request->monopen,'close' => $request->monclose));
        } else {
            $emp->mon = json_encode(array('open' => Carbon::parse($request->monopen)->format('H:i'),'close' => Carbon::parse($request->monclose)->format('H:i')));
        }
  
        if($request->tueopen == null || $request->tueclose == null){
            $emp->tue = json_encode(array('open' => $request->tueopen,'close' => $request->tueclose));
        } else {
            $emp->tue = json_encode(array('open' => Carbon::parse($request->tueopen)->format('H:i'),'close' => Carbon::parse($request->tueclose)->format('H:i')));
        }

        if($request->wedopen == null || $request->wedclose == null){
            $emp->wed = json_encode(array('open' => $request->wedopen,'close' => $request->wedclose));
        } else {
            $emp->wed = json_encode(array('open' => Carbon::parse($request->wedopen)->format('H:i'),'close' => Carbon::parse($request->wedclose)->format('H:i')));
        }

        if($request->thuopen == null || $request->thuclose == null){
            $emp->thu = json_encode(array('open' => $request->thuopen,'close' => $request->thuclose));
        } else {
            $emp->thu = json_encode(array('open' => Carbon::parse($request->thuopen)->format('H:i'),'close' => Carbon::parse($request->thuclose)->format('H:i')));
        }

        if($request->friopen == null || $request->friclose == null){
            $emp->fri = json_encode(array('open' => $request->friopen,'close' => $request->friclose));
        } else {
            $emp->fri = json_encode(array('open' => Carbon::parse($request->friopen)->format('H:i'),'close' => Carbon::parse($request->friclose)->format('H:i')));
        }

        if($request->satopen == null || $request->satclose == null){
            $emp->sat = json_encode(array('open' => $request->satopen,'close' => $request->satclose));
        } else {
            $emp->sat = json_encode(array('open' => Carbon::parse($request->satopen)->format('H:i'),'close' => Carbon::parse($request->satclose)->format('H:i')));
        }
        
        $emp->save();
        return redirect('/admin/employee');
    }
    
    public function destroy($id)
    {
        $emp = Employee::find($id);
        $emp->status = 0;
        $emp->save();
        return redirect('/admin/employee');
    }
    // public function hideEmp(Request $request)
    // {
    //     $emp = Employee::find($request->empId);
    //     if ($emp->status == 0) 
    //     {   
    //         $emp->status = 1;
    //         $emp->save();
    //     }
    //     else if($emp->status == 1)
    //     {
    //         $emp->status = 0;
    //         $emp->save();
    //     }
    // }
}
