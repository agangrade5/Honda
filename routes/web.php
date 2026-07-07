<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('manage-events.index');
    }

    return redirect()->route('login');
});

// Guest users only
Route::middleware(['guest', 'no.cache'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
    });
});

// Authenticated users only
Route::middleware(['admin.auth', 'no.cache'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Public Event Report routes (openable without login)
Route::controller(App\Http\Controllers\Backend\ReportController::class)->group(function () {
    // PascalCase paths (default named routes)
    Route::get('/ReportingDashboardView/{encodedId}', 'publicReport')->name('event.public-report');
    Route::get('/ReportingDashboardView/{encodedId}/demo-reports', 'publicDemoReports')->name('event.public-report.demo');
    Route::get('/ReportingDashboardView/{encodedId}/demo-reports-2', 'publicDemoReports2')->name('event.public-report.demo2');
    Route::get('/ReportingDashboardView/{encodedId}/stats-reports', 'publicStatsReports')->name('event.public-report.stats');
    Route::get('/ReportingDashboardView/{encodedId}/demo-graph-reports', 'publicDemoGraphReports')->name('event.public-report.graph');
    Route::get('/ReportingDashboardView/{encodedId}/nhra-reports', 'publicNHRAReports')->name('event.public-report.nhra');

    // lowercase paths (aliases for backward compatibility)
    Route::get('/reportingdashboardview/{encodedId}', 'publicReport');
    Route::get('/reportingdashboardview/{encodedId}/demo-reports', 'publicDemoReports');
    Route::get('/reportingdashboardview/{encodedId}/demo-reports-2', 'publicDemoReports2');
    Route::get('/reportingdashboardview/{encodedId}/stats-reports', 'publicStatsReports');
    Route::get('/reportingdashboardview/{encodedId}/demo-graph-reports', 'publicDemoGraphReports');
    Route::get('/reportingdashboardview/{encodedId}/nhra-reports', 'publicNHRAReports');

    Route::get('/ReportingDashboardView.php', 'legacyPublicReport');
});

Route::match(['get', 'post'], '/register', [App\Http\Controllers\Backend\Auth\RegistrationController::class, 'index'])->name('register.index');
Route::match(['get', 'post'], '/API/APICloud.php', [App\Http\Controllers\Api\APICloudController::class, 'handle'])->name('api.apicloud');


