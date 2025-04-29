<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationService extends Model
{
    protected $table = 'location_services';

    protected $fillable = ['location_id', 'service_id', 'price'];

    public function location()
    {
        return $this->belongsTo(LocationPoint::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
