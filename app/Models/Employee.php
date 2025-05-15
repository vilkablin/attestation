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

        if (!isset($schedule['start_date'], $schedule['work_days'], $schedule['rest_days'])) {
            return false;
        }

        $startDate = Carbon::parse($schedule['start_date']);

        // Округляем разницу в днях до целого числа
        $diffInDays = $startDate->diffInDays($date);

        $cycleLength = $schedule['work_days'] + $schedule['rest_days'];
        $positionInCycle = $diffInDays % $cycleLength;

        return $positionInCycle < $schedule['work_days'];
    }

    public function setWorkScheduleAttribute($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Work schedule must be an array');
        }
        $this->attributes['work_schedule'] = json_encode($value);
    }

    public function getWorkScheduleAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    public function availableHoursForDate(Carbon $date, int $serviceDuration): array
    {
        if (!$this->isWorkingDay($date)) {
            return [];
        }

        $schedule = $this->work_schedule;

        $start = Carbon::parse($schedule['work_hours']['start'])->copy()->setDateFrom($date);
        $end = Carbon::parse($schedule['work_hours']['end'])->copy()->setDateFrom($date);

        $hours = [];

        $appointments = $this->appointments()
            ->whereDate('date', $date->toDateString())
            ->where('status_id', 1)
            ->get(['date', 'end_date']);

        while ($start->lt($end)) {
            $slotStart = $start->copy();
            $slotEnd = $slotStart->copy()->addMinutes($serviceDuration);

            // если конец выходит за пределы рабочего дня — прерываем
            if ($slotEnd->gt($end)) {
                break;
            }

            $isOverlapping = $appointments->contains(function ($appointment) use ($slotStart, $slotEnd) {
                $appointmentStart = Carbon::parse($appointment->date);
                $appointmentEnd = Carbon::parse($appointment->end_date);

                return $slotStart->lt($appointmentEnd) && $slotEnd->gt($appointmentStart);
            });

            if (!$isOverlapping) {
                $hours[] = $slotStart->format('H:i');
            }

            $start->addMinutes(60); // шаг между слотами, можно изменить на 30/15
        }

        return $hours;
    }
}
