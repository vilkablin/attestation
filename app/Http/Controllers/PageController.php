<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;


class PageController extends Controller
{
    public function showMainPage()
    {
        $services = Service::select('id', 'name as title', 'base_price as price', 'image')->get();
        return Inertia::render('Home', [
            'title' => 'Автомойка "Чистая машина"',
            'services' => $services,

        ]);
    }

    public function showService($id)
    {
        $service = Service::select('id', 'name as title', 'base_price as price', 'image', 'description')
            ->findOrFail($id);

        return Inertia::render('Service', [
            'service' => $service
        ]);
    }
}
