<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeService extends Model
{
  protected $table = 'employee_service';

  protected $fillable = [
    'employee_id',
    'service_id',
  ];

  public function employee(): BelongsTo
  {
    return $this->belongsTo(Employee::class);
  }

  public function service(): BelongsTo
  {
    return $this->belongsTo(Service::class);
  }
}
