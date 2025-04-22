<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showMainPage()
    {
        return Inertia::render('Home/page', [
            'title' => 'Автомойка "Чистая машина"',
            'promotions' => [
                ['id' => 1, 'name' => 'Комплексная мойка', 'discount' => '20%'],
                ['id' => 2, 'name' => 'Химчистка салона', 'discount' => '15%'],
            ]
        ]);
    }
}
