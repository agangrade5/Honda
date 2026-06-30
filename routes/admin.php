<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ManageRegionsController;

Route::get('/manage-regions', [ManageRegionsController::class, 'index'])
    ->name('manage-regions.index');
