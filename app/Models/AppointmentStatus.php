<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentStatus extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_CANCELLED = 4;
    public const STATUS_ACTIVE = 5;

    protected $fillable = ['name', 'color'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
