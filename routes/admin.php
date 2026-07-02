<?php

use App\Http\Controllers\Backend\{
    EventController,
    TruckController,
    InventoryController,
    RegionsController,
    SocialMediaController,
    CountryController,
    DealerController
};
use Illuminate\Support\Facades\Route;

//Resource Route
Route::middleware(['admin.auth', 'no.cache'])->group(function () {
    Route::post('manage-trucks/import', [TruckController::class, 'import'])->name('manage-trucks.import');
    Route::post('manage-countries/states/add', [CountryController::class, 'addState'])->name('manage-countries.states.add');
    Route::post('manage-countries/states/edit', [CountryController::class, 'editState'])->name('manage-countries.states.edit');
    Route::post('manage-countries/states/delete', [CountryController::class, 'deleteState'])->name('manage-countries.states.delete');
    Route::resources(
        [
            'manage-events' => EventController::class,
            'manage-trucks' => TruckController::class,
            'manage-inventory' => InventoryController::class,
            'manage-regions' => RegionsController::class,
            'manage-social-media' => SocialMediaController::class,
            'manage-countries' => CountryController::class,
            'manage-dealers' => DealerController::class
        ]
    );
});
