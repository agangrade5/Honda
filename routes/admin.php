<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RegionsController;

Route::get('/manage-regions', [RegionsController::class, 'index'])
    ->name('manage-regions.index');
