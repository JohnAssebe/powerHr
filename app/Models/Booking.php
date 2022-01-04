<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';
    public $primaryKey = 'id';
    public $timestamps = true;
    public $appends = ['userDetails','empDetails'];


    public function organization()
    {
        return $this->hasOne(Organization::class, 'organization_id', 'organization_id');
    }
    public function employee()
    {
        return $this->hasOne(Employee::class, 'emp_id', 'emp_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getUserDetailsAttribute()
    {
        return User::find($this->user_id);
    }
    public function getEmpDetailsAttribute()
    {
        return Employee::find($this->emp_id);
    }
}
