<?php

use App\Http\Controllers\Exports\ExportOriginalSheetController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::resource('uploads', UploadController::class)->only(['store', 'index', 'destroy']);

    Route::group([
        'prefix' => 'export',
        'middleware' => ['can:view,upload']
    ], function () {
        Route::get('uploads/{upload}/original', ExportOriginalSheetController::class)->name('export_original_sheet');
    });
});

require __DIR__ . '/auth.php';
