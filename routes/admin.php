<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RegionsController;

Route::get('/manage-regions', [RegionsController::class, 'index'])
    ->name('manage-regions.index');
Route::post('/manage-regions/store', [RegionsController::class, 'store'])
    ->name('manage-regions.store');
Route::post('/manage-regions/update', [RegionsController::class, 'update'])
    ->name('manage-regions.update');
Route::post('/manage-regions/delete', [RegionsController::class, 'destroy'])
    ->name('manage-regions.destroy');
