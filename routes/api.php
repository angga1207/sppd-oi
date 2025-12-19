<?php

use App\Http\Controllers\API\MobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/spt/list', [MobileController::class, 'getSuratPerintahList'])->name('api.spt.list');
Route::get('/spt/{id}/detail', [MobileController::class, 'getSuratPerintahDetail'])->name('api.spt.detail');
Route::get('/spt/{id}/generate', [MobileController::class, 'getSuratPerintahGenerate'])->name('api.spt.generate');
Route::get('/sppd/{id}/detail', [MobileController::class, 'getSppdDetail'])->name('api.sppd.detail');

Route::post('/spt/{id}/reject', [MobileController::class, 'rejectSPT'])->name('api.spt.reject');
Route::post('/spt/{id}/approve', [MobileController::class, 'approveSPT'])->name('api.spt.approve');
