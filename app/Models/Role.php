<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public const USER = 1;
    public const ADMIN = 2;
    public const OWNER = 3;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
