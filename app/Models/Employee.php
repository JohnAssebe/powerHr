<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    public $primaryKey = 'emp_id';
    public $timestamps = true;
    public $appends = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'organization'];
    protected $guarded = [];
    public function getSundayAttribute()
    {
        return json_decode($this->sun, true);
    }
    public function getMondayAttribute()
    {
        return json_decode($this->mon, true);
    }
    public function getTuesdayAttribute()
    {
        return json_decode($this->tue, true);
    }
    public function getWednesdayAttribute()
    {
        return json_decode($this->wed, true);
    }
    public function getThursdayAttribute()
    {
        return json_decode($this->thu, true);
    }
    public function getFridayAttribute()
    {
        return json_decode($this->fri, true);
    }
    public function getSaturdayAttribute()
    {
        return json_decode($this->sat, true);
    }

    public function getOrganizationAttribute()
    {
        $organization = Organization::find($this->attributes['organization_id']);
        return $organization;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescription(){
        return $this->hasMany(Prescription::class);
    }
}
