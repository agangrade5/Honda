<?php

use App\Http\Controllers\Backend\{
    EventController,
    TruckController,
    InventoryController,
    RegionsController,
    SocialMediaController,
    CountryController,
    DealerController,
    RestrictedRiderController
};
use Illuminate\Support\Facades\Route;

//Admin Route
Route::middleware(['admin.auth', 'no.cache'])->group(function () {
    //Truck Import
    Route::post('manage-trucks/import', [TruckController::class, 'import'])->name('manage-trucks.import');
    // Manage Countries States Routes
    Route::controller(CountryController::class)->group(function () {
        Route::post('manage-countries/states/add', 'addState')->name('manage-countries.states.add');
        Route::post('manage-countries/states/edit', 'editState')->name('manage-countries.states.edit');
        Route::post('manage-countries/states/delete', 'deleteState')->name('manage-countries.states.delete');
    });
    // Resource Routes
    Route::resources(
        [
            'manage-events' => EventController::class,
            'manage-trucks' => TruckController::class,
            'manage-inventory' => InventoryController::class,
            'manage-regions' => RegionsController::class,
            'manage-social-media' => SocialMediaController::class,
            'manage-countries' => CountryController::class,
            'manage-dealers' => DealerController::class,
            'manage-restricted-riders' => RestrictedRiderController::class
        ]
    );
});
