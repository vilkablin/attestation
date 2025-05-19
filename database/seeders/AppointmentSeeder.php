<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\LocationPoint;
use App\Models\AppointmentStatus;
use App\Models\Promocode;
use App\Models\Employee;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $services = Service::all();
        $locations = LocationPoint::all();
        $statuses = AppointmentStatus::all();
        $promocodes = Promocode::all();
        $employees = Employee::all();

        if ($users->isEmpty() || $services->isEmpty() || $locations->isEmpty() || $statuses->isEmpty()) {
            $this->command->info('Не хватает данных для создания записей. Проверьте сидеры пользователей, услуг, локаций и статусов.');
            return;
        }

        // Создадим 10 тестовых записей
        for ($i = 0; $i < 10; $i++) {
            $user = $users->random();
            $service = $services->random();
            $location = $locations->random();
            $status = $statuses->random();

            // Дата записи: от завтра до через 30 дней
            $startDate = now()->addDays(rand(1, 30))->setTime(rand(8, 18), 0);
            $endDate = (clone $startDate)->addMinutes($service->base_time);

            $price = $service->base_price;

            // Случайно применим промокод в 30% случаев
            $promocode = null;
            if (rand(1, 100) <= 30 && !$promocodes->isEmpty()) {
                $promocode = $promocodes->random();
                if ($promocode->isActive()) {
                    $discount = ($price * $promocode->discount) / 100;
                    $price -= $discount;
                } else {
                    $promocode = null;
                }
            }

            $appointment = Appointment::create([
                'date' => $startDate,
                'end_date' => $endDate,
                'service_id' => $service->id,
                'location_id' => $location->id,
                'status_id' => $status->id,
                'user_id' => $user->id,
                'price' => $price,
                'comment' => 'Тестовая запись #' . Str::random(5),
                'promocode_id' => $promocode ? $promocode->id : null,
            ]);

            // Привяжем к записи случайных сотрудников (1-3)
            if (!$employees->isEmpty()) {
                $assignedEmployees = $employees->random(rand(1, min(3, $employees->count())));
                $appointment->employees()->sync($assignedEmployees->pluck('id')->toArray());
            }
        }
    }
}
