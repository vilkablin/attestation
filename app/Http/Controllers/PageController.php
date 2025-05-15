<?php

namespace App\Http\Controllers;

use App\Models\LocationPoint;
use App\Models\LocationService;
use App\Models\Service;
use Inertia\Inertia;


class PageController extends Controller
{
    public function showMainPage()
    {
        $services = Service::select('id', 'name as title', 'base_price as price', 'image')->limit(3)->get();
        return Inertia::render('Home', [
            'title' => 'Автомойка "Чистая машина"',
            'services' => $services,

        ]);
    }

    public function showService($id)
    {
        $service = Service::select('id', 'name as title', 'base_price as price', 'image', 'description', 'base_time as time')
            ->findOrFail($id);

        $locationService = LocationService::where('service_id', $service->id)->get();
        $locations = [];
        foreach ($locationService as $item) {
            array_push($locations, LocationPoint::where('id', $item->location->id)->get()->select('address'));
        }
        return Inertia::render('Service', [
            'service' => $service,
            'locations' => $locations,
        ]);
    }
}
