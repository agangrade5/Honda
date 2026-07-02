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
    ReportController,
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
            'manage-groups' => GroupController::class
        ]
    );

    // Event Report routes
    Route::get('manage-events/{encodedId}/report', [ReportController::class, 'show'])->name('event.report.show');
    Route::get('manage-events/{encodedId}/report/demo-reports', [ReportController::class, 'demoReports'])->name('event.report.demo');
    Route::get('manage-events/{encodedId}/report/demo-reports-2', [ReportController::class, 'demoReports2'])->name('event.report.demo2');
    Route::get('manage-events/{encodedId}/report/stats-reports', [ReportController::class, 'statsReports'])->name('event.report.stats');
    Route::get('manage-events/{encodedId}/report/demo-graph-reports', [ReportController::class, 'demoGraphReports'])->name('event.report.graph');
});
