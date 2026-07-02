<?php

use App\Http\Controllers\Backend\{
    EventController,
    TruckController,
    InventoryController,
    RegionsController,
    SocialMediaController,
    CountryController,
    DealerController,
    RestrictedRiderController,
    GroupController,
    ImportVehiclesController,
    SignedWaiverController,
    ModelsController,
    UserController,
};
use Illuminate\Support\Facades\Route;

//Admin Route
Route::middleware(['admin.auth', 'no.cache'])->group(function () {
    //Truck Import
    Route::post('manage-trucks/import', [TruckController::class, 'import'])->name('manage-trucks.import');
    // Manage Countries States Routes
    Route::prefix('manage-countries/states')
        ->name('manage-countries.states.')
        ->controller(CountryController::class)
        ->group(function () {
            Route::post('/add', 'addState')->name('add');
            Route::post('/edit', 'editState')->name('edit');
            Route::post('/delete', 'deleteState')->name('delete');
        });
    // Manage Signed Waiver
    Route::get('manage-signed-waivers', [SignedWaiverController::class, 'index'])->name('manage-signed-waivers.index');
    // Manage Signed Waiver
    Route::get('manage-import-vehicles', [ImportVehiclesController::class, 'index'])->name('manage-import-vehicles.index');
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
            'manage-restricted-riders' => RestrictedRiderController::class,
            'manage-groups' => GroupController::class,
            'manage-models' => ModelsController::class,
            'manage-users' => UserController::class,
        ]
    );
});
