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
    WaiverController,
    EmailTemplateController,
    SmsTemplateController,
    ReportController,
    SurveyController,
    SurveyQuestionController,
    SurveyAnswerController,
    DataManagementController,
    BikeAndTimeController,
    PreRegEmailController,
    PreRegHtmlController,
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
    // Manage Signed Waiver Routes
    Route::prefix('manage-signed-waivers')
        ->name('manage-signed-waivers.')
        ->controller(SignedWaiverController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/data', 'getData')->name('data');
            Route::get('/pdf/{id}', 'downloadPdf')->name('pdf');
        });
    // Manage Import Vehicles Routes
    Route::prefix('manage-import-vehicles')
        ->name('manage-import-vehicles.')
        ->controller(ImportVehiclesController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/upload', 'upload')->name('upload');
            Route::post('/delete', 'deleteFile')->name('delete');
            Route::post('/read', 'readExcel')->name('read');
        });
    // Event Report routes
    Route::prefix('manage-events/{encodedId}/report')
        ->name('event.report.')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/demo-reports', 'demoReports')->name('demo');
            Route::get('/demo-reports-2', 'demoReports2')->name('demo2');
            Route::get('/stats-reports', 'statsReports')->name('stats');
            Route::get('/demo-graph-reports', 'demoGraphReports')->name('graph');
        });
    // Manage Email Templates Routes
    Route::prefix('manage-email-templates')
        ->name('manage-email-templates.')
        ->controller(EmailTemplateController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::post('/send-test', 'sendTestEmail')->name('send-test');
            Route::put('/{manage_email_template}', 'update')->name('update');
            Route::delete('/{manage_email_template}', 'destroy')->name('destroy');
        });

    // Manage SMS Templates Routes
    Route::prefix('manage-sms-templates')
        ->name('manage-sms-templates.')
        ->controller(SmsTemplateController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{manage_sms_template}', 'update')->name('update');
            Route::delete('/{manage_sms_template}', 'destroy')->name('destroy');
        });

    // Manage Data Management Routes
    Route::get('manage-data-management', [DataManagementController::class, 'index'])->name('manage-data-management.index');
    // Manage PreReg Email Routes
    Route::match(['get', 'post'], 'manage-pre-reg-email', [PreRegEmailController::class, 'index'])->name('manage-pre-reg-email.index');
    Route::post('manage-pre-reg-email/send', [PreRegEmailController::class, 'sendEmail'])->name('manage-pre-reg-email.send');
    // Manage PreReg Html Routes
    Route::get('manage-pre-reg-html', [PreRegHtmlController::class, 'index'])->name('manage-pre-reg-html.index');
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
            'manage-waivers' => WaiverController::class,
            'manage-surveys' => SurveyController::class,
            'manage-survey-questions' => SurveyQuestionController::class,
            'manage-survey-answers' => SurveyAnswerController::class,
            'manage-bikes-and-times' => BikeAndTimeController::class,
        ]
    );
});
