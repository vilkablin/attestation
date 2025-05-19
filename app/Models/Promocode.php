<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promocode extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_to',
        'usage_limit',
        'used_count',
        'description',
        'status_id',
        'user_id'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(PromocodeStatus::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status_id === 1 && // 1 = Active
            now()->between($this->valid_from, $this->valid_to) &&
            ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function apply(): void
    {
        $this->increment('used_count');
    }

    public function scopeValid($query)
    {
        return $query->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->whereColumn('used_count', '<', 'usage_limit');
    }
}
