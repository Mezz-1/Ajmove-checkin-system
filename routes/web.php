<?php

use App\Http\Controllers\Admin\CheckinController;
use Illuminate\Support\Facades\Route;
Route::view('/','welcome');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin.index');
    
    Route::get('/checkin/scan/{id}', [CheckinController::class, 'scan'])->name('checkin.scan');
    Route::post('/checkin/toggle/{registration}', [CheckinController::class, 'togglePresence'])->name('checkin.toggle');
});
