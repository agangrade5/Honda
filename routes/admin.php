<?php
 
use App\Http\Controllers\Backend\{
    RegionsController,
    InventoryController,
};
use Illuminate\Support\Facades\Route;
 
//Resource Route
Route::resource('manage-regions', RegionsController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('manage-inventory', InventoryController::class);
 