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
        'working_hours',
        'phone',
        'coordinates'
    ];

    protected $casts = [
        'working_hours' => 'json',
        'coordinates' => 'json'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'location_services')
            ->withPivot('price');
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Employee::class);
    }
}
