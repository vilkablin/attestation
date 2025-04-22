<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocodeStatus extends Model
{
    public const ACTIVE = 1;
    public const INACTIVE = 2;
    public const EXPIRED = 3;

    protected $fillable = ['name', 'color'];

    public function promocodes()
    {
        return $this->hasMany(Promocode::class);
    }
}
