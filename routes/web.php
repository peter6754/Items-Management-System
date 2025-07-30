<?php

use App\Http\Controllers\FetchController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::resource('items', ItemController::class);
Route::post('items/generate', [ItemController::class, 'generate'])->name('items.generate');
Route::delete('items-clear', [ItemController::class, 'clear'])->name('items.clear');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
Route::post('settings/test', [SettingController::class, 'testConnection'])->name('settings.test');
Route::post('settings/sync', [SettingController::class, 'sync'])->name('settings.sync');

Route::get('fetch', [FetchController::class, 'fetch'])->name('fetch');
Route::get('fetch/{count}', [FetchController::class, 'fetch'])->name('fetch.count')->where('count', '[0-9]+');
