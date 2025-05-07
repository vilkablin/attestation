<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    protected $fillable = [
        'date',
        'end_date',
        'service_id',
        'location_id',
        'status_id',
        'user_id',
        'car_id',
        'price',
        'comment',
        'promocode_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'end_date' => 'datetime'
    ];

    // Связь с услугой
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // Связь с точкой мойки
    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationPoint::class);
    }

    // Связь со статусом записи
    public function status(): BelongsTo
    {
        return $this->belongsTo(AppointmentStatus::class);
    }

    // Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // Связь с промокодом
    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class);
    }

    // Связь с сотрудниками (many-to-many)
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_appointment')
            ->withTimestamps();
    }

    // Проверка, активна ли запись
    public function isActive(): bool
    {
        return $this->status_id === AppointmentStatus::STATUS_ACTIVE;
    }

    public function applyPromocode(Promocode $promocode): bool
    {
        if (!$promocode->isActive()) {
            return false;
        }

        $this->promocode()->associate($promocode);
        $discount = ($this->price * $promocode->discount) / 100;
        $this->price -= $discount;
        $this->save();

        $promocode->apply();

        return true;
    }
}
