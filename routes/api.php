<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;

Route::middleware('api')
  ->post('/telegram/webhook', [BotController::class, 'webhook']);
