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

class AppointmentController extends Controller
{

    public function availableHours(Request $request, $locationId)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->input('date'));
        $location = LocationPoint::with('employees')->findOrFail($locationId);

        $availableHours = [];

        foreach ($location->employees as $employee) {
            $hours = $employee->availableHoursForDate($date);
            foreach ($hours as $hour) {
                $availableHours[] = [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'time' => $hour,
                ];
            }
        }

        return Inertia::render('Appointment', [
            'services' => Service::all(),
            'locations' => LocationPoint::with('employees')->get(),
            'availableHours' => $availableHours,
        ]);
    }
    public function create()
    {
        $services = Service::all();
        $locations = LocationPoint::with('employees')->get();
        return Inertia::render('Appointment', [
            'services' => $services,
            'locations' => $locations
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'location_id' => 'required|exists:location_points,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
        ]);

        $service = Service::find($request->service_id);
        $employee = Employee::find($request->employee_id);
        $location = LocationPoint::find($request->location_id);
        $start_date = \Carbon\Carbon::parse($request->date);
        $end_date = $start_date->copy()->addMinutes($service->base_time);

        if (!$employee->isWorkingDay($start_date)) {
            return back()->withErrors(['error' => 'Employee is not working on this day.']);
        }

        $serviceLocation = LocationService::where('service_id', $service->id)->where('location_id', $location->id)->first();
        $price = $serviceLocation ? $serviceLocation->price : null;


        $appointment = new Appointment([
            'service_id' => $service->id,
            'location_id' => $location->id,
            'user_id' => auth()->id(),
            'date' => $start_date,
            'end_date' => $end_date,
            'status_id' => 1,
            'price' => $price,
        ]);
        $appointment->save();

        $appointment->employees()->attach($employee->id);

        return redirect('/dashboard')->with('success', 'Appointment created successfully!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status_id == 1) {

            $appointment->employees()->detach();

            $appointment->delete();

            return redirect()->route('dashboard')->with('success', 'Запись отменена.');
        }

        return back()->withErrors(['error' => 'Вы не можете отменить эту запись.']);
    }
}
