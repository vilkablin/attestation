<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\LocationPoint;
use App\Models\LocationService;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\Promocode;

class AppointmentController extends Controller
{

    public function checkPromocode(Request $request)
    {
        $code = $request->input('code');
        $serviceId = $request->input('service_id');

        // Проверяем существование промокода в базе данных
        $promo = Promocode::where('code', $code)
            ->where('status_id', 1)
            ->first();

        // Если промокод найден и активен
        if ($promo && $promo->isActive()) {
            $discount = $promo->discount;

            // Возвращаем ответ через Inertia, передавая скидку
            return Inertia::render('Appointment', [
                'discount' => $discount,
            ]);
        }

        // Если промокод не найден или не активен, возвращаем null
        return Inertia::render('Appointment', [
            'discount' => null,
        ]);
    }

    public function availableHours(Request $request, $locationId)
    {
        $request->validate([
            'date' => 'required|date',
            'service_id' => 'required|exists:services,id',
        ]);

        $date = Carbon::parse($request->input('date'));
        $serviceId = $request->input('service_id');

        // Получаем локацию и загружаем сотрудников
        $location = LocationPoint::with('employees')->findOrFail($locationId);
        $service = Service::findOrFail($serviceId);
        $serviceDuration = $service->base_time;

        $availableHours = [];

        foreach ($location->employees as $employee) {
            // Проверяем, работает ли сотрудник в этот день
            if (!$employee->isWorkingDay($date)) {
                continue;
            }

            $hours = $employee->availableHoursForDate($date, $serviceDuration);

            foreach ($hours as $hour) {
                $availableHours[] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'time' => $hour,
                ];
            }
        }

        return Inertia::render('Appointment', [
            'service' => $service,
            'locations' => LocationPoint::with('employees')->get(),
            'availableHours' => $availableHours,
        ]);
    }

    public function create(Service $service)
    {
        $locations = LocationPoint::whereHas('locationServices', function ($query) use ($service) {
            $query->where('service_id', $service->id);
        })
            ->with(['employees', 'locationServices' => function ($query) use ($service) {
                $query->where('service_id', $service->id);
            }])
            ->get();


        return Inertia::render('Appointment', [
            'service' => $service,
            'locations' => $locations,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'location_id' => 'required|exists:location_points,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'promocode' => 'nullable|string',
        ]);

        $service = Service::find($request->service_id);
        $employee = Employee::find($request->employee_id);
        $location = LocationPoint::find($request->location_id);
        $start_date = Carbon::parse($request->date);
        $end_date = $start_date->copy()->addMinutes($service->base_time);

        if (!$employee->isWorkingDay($start_date)) {
            return back()->withErrors(['error' => 'Сотрудник не работает в этот день.']);
        }

        // Получаем цену услуги для выбранной локации
        $serviceLocation = LocationService::where('service_id', $service->id)
            ->where('location_id', $location->id)
            ->first();

        $price = $serviceLocation ? $serviceLocation->price : $service->price;

        // Обработка промокода
        $promo = null;
        if ($request->filled('promocode')) {
            $promo = Promocode::where('code', $request->promocode)
                ->where('status_id', 1)
                ->first();

            if ($promo && $promo->isActive()) {
                $discount = $promo->discount;
                // Применяем скидку к цене
                $price = $price - ($price * $discount / 100);
                $promo->apply(); // Увеличиваем счетчик использований
            } else {
                $promo = null;
            }
        }

        $appointment = new Appointment([
            'service_id' => $service->id,
            'location_id' => $location->id,
            'user_id' => auth()->id(),
            'date' => $start_date,
            'end_date' => $end_date,
            'status_id' => 1,
            'price' => $price,
            'promocode_id' => $promo?->id,
        ]);
        $appointment->save();

        $appointment->employees()->attach($employee->id);

        return redirect('/dashboard')->with('success', 'Запись успешно создана!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status_id == 1) {

            $appointment->employees()->detach();

            $appointment->delete();

            return redirect('/dashboard')->with('success', 'Запись отменена.');
        }

        return back()->withErrors(['error' => 'Вы не можете отменить эту запись.']);
    }
}
