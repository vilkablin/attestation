<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Service;

class EmployeeServiceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $services = Service::all();

        // Например, первый сотрудник умеет делать первую и вторую услугу
        if ($employees->count() && $services->count()) {
            $employees[0]->services()->sync([$services[0]->id, $services[1]->id]);

            // Второй сотрудник умеет делать вторую и третью услугу
            if (isset($employees[1])) {
                $employees[1]->services()->sync([$services[1]->id, $services[2]->id]);
            }
        }
    }
}
