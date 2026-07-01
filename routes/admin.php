<?php
 
use App\Http\Controllers\Backend\{
    RegionsController,
    InventoryController,
    TruckController
};
use Illuminate\Support\Facades\Route;

//Resource Route
Route::middleware('auth')->group(function () {
    Route::resources(
        [
            'manage-regions' => RegionsController::class,
            'manage-inventory' => InventoryController::class,
            'manage-trucks' => TruckController::class,
        ]
    );
});
