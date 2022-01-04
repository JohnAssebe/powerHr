<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatement extends Model
{
    use HasFactory;

    public $appends = ['userDetails'];


    public function getUserDetailsAttribute()
    {
        return User::find($this->patient_id);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
