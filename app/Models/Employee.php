<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'location_id',
        'specialization',
        'work_schedule'
    ];

    protected $casts = [
        'work_schedule' => 'json'
    ];

    public function location()
    {
        return $this->belongsTo(LocationPoint::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service');
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'employee_appointment');
    }
}