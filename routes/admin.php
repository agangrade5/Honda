<?php

use App\Http\Controllers\Backend\{
    EventController,
    TruckController,
    InventoryController,
    RegionsController,
};
use Illuminate\Support\Facades\Route;

//Resource Route
Route::resources(
    [
        'manage-events' => EventController::class,
        'manage-trucks' => TruckController::class,
        'manage-inventory' => InventoryController::class,
        'manage-regions' => RegionsController::class,
    ]
);
