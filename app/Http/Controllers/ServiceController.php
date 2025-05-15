<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class ServiceController extends Controller
{


  public function index(HttpRequest $request)
  {
    $query = Service::query();

    if ($search = $request->input('search')) {
      $query->where('name', 'like', "%{$search}%");
    }

    if ($priceFrom = $request->input('price_from')) {
      $query->where('base_price', '>=', $priceFrom);
    }

    if ($priceTo = $request->input('price_to')) {
      $query->where('base_price', '<=', $priceTo);
    }

    $services = $query->latest()->select('id', 'name as title', 'base_price as price', 'image', 'description', 'base_time as time')->get();

    return Inertia::render('Services', [
      'services' => $services,
      'filters' => $request->only(['search', 'price_from', 'price_to']),
    ]);
  }
}
