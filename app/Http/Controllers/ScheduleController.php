<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function scheduleSession($var = null)
    {
        // save the schedule info into the schedule table. 
        // It can include date, time, type, client the schedule is with
    }

    public function remind(){
        // send an email when the time is due for the session to begin
    }

    // the therapy functions as follows
    // 1. the user has a 1 hour session 
    // 2. both online and physical sessions are treated the same
    // 3. the schedule runs at the same time for either preselected weeks 
    // or when the therapists deems it fit 
    // maybe have some type of scoring system to track progress ***very optional***

    public function completeTherapy( $var = null)
    {
        // - decided by the therapist
        // - when this happens the schedule would be cleared for other patients
    }    
}
