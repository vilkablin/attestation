<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationPoint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'address',
        'places_count',
        'phone',
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class, 'location_id');
    }


    public function services()
    {
        return $this->belongsToMany(Service::class, 'location_services')
            ->withPivot('price');
    }

    public function locationServices()
    {
        return $this->hasMany(LocationService::class, 'location_id');
    }


    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Employee::class);
    }
}
