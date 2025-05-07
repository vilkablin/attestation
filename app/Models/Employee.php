<?php

namespace App\Models;

use Carbon\Carbon;
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
        'work_schedule' => 'array'
    ];

    public function location()
    {
        return $this->belongsTo(LocationPoint::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service', 'employee_id', 'service_id');
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'employee_appointment');
    }

    public function isWorkingDay(Carbon $date): bool
    {
        $schedule = $this->work_schedule;
        if (!$schedule) {
            return false;
        }

        $startDate = Carbon::parse($schedule['start_date']);

        // Округляем разницу в днях до целого числа
        $diffInDays = $startDate->diffInDays($date);

        $cycleLength = $schedule['work_days'] + $schedule['rest_days'];
        $positionInCycle = $diffInDays % $cycleLength;

        return $positionInCycle < $schedule['work_days'];
    }

    public function availableHoursForDate(Carbon $date): array
    {
        if (!$this->isWorkingDay($date)) {

            return [];
        }

        $schedule = $this->work_schedule;
        $start = Carbon::parse($schedule['work_hours']['start']);
        $end = Carbon::parse($schedule['work_hours']['end']);

        $hours = [];

        while ($start->lt($end)) {
            $hours[] = $start->format('H:i');
            $start->addHour();
        }

        return $hours;
    }
}
