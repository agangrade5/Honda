<?php

use App\Http\Controllers\Backend\{
    RegionsController,
    InventoryController
};
use Illuminate\Support\Facades\Route;

Route::get('/manage-regions', [RegionsController::class, 'index'])
    ->name('manage-regions.index');

Route::get('/manage-inventory', [InventoryController::class, 'index'])
    ->name('manage-inventory.index');

