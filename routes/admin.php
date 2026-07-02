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

    Route::prefix('manage-countries')->name('manage-countries.')->group(function () {
        Route::post('states/add', [CountryController::class, 'addState'])->name('states.add');
        Route::post('states/edit', [CountryController::class, 'editState'])->name('states.edit');
        Route::post('states/delete', [CountryController::class, 'deleteState'])->name('states.delete');
    });

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
