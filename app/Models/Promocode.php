<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promocode extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_to',
        'usage_limit',
        'used_count',
        'status_id',
        'user_id',
        'description'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    // Связь со статусом
    public function status(): BelongsTo
    {
        return $this->belongsTo(PromocodeStatus::class);
    }

    // Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Связь с записями
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Проверка активности
    public function isActive(): bool
    {
        return $this->status_id === PromocodeStatus::ACTIVE &&
            now()->between($this->valid_from, $this->valid_to) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    // Применение промокода
    public function apply()
    {
        if ($this->usage_limit !== null) {
            $this->increment('used_count');
        }
    }
}
