<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_time',
        'base_price',
        'image'
    ];

    public function locations()
    {
        return $this->belongsToMany(LocationPoint::class, 'location_services')
            ->withPivot('price');
    }


    public function locationServices()
    {
        return $this->hasMany(LocationService::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_service', 'service_id', 'employee_id');
    }
}
