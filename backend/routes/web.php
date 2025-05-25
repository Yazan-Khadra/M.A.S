<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test-image/{filename}', function ($filename) {
    $path = "public/images/{$filename}";
    
    if (!Storage::exists($path)) {
        abort(404);
    }
    
    return response()->file(Storage::path($path));
});
