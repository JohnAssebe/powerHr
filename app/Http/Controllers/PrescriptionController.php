<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prescription = new Prescription();
        $prescription->employee_id = $request->therapist_id;
        $prescription->patient_id = $request->patient_id;
        $prescription->medication = $request->medication;
        $prescription->instruction = $request->instruction;
        $prescription->save();
        return response()->json(['msg' => 'saved record successfully', 'status' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show($patient_id)
    {
        $prescription = Prescription::where('patient_id', $patient_id)->get();
        // dd(Prescription::where('patient_id', 1)->with('user')->get());
        return   response()
            ->json(["data" => $prescription, 'msg' => 'record successfully retrieved', 'status' => true], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $prescription = Prescription::where('patient_id', $request->patient_id)->first();
        $prescription->employee_id = $request->employee_id;
        $prescription->medication = $request->medication;
        $prescription->instruction = $request->instruction;
        $prescription->save();
        return response()->json(['msg' => 'updated record successfully', 'status' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy($prescription_id)
    {
        $prescription = Prescription::findOrFail($prescription_id);
        $prescription->delete();
        return   response()
            ->json(['msg' => 'record successfully deleted', 'status' => true], 200);
    }
}
