<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotSession extends Model
{
    protected $fillable = ['telegram_id', 'data', 'step'];

    protected $casts = [
        'data' => 'array',
    ];
}
