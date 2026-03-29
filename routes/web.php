<?php

use App\Http\Controllers\CollectionAttributeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('collections', CollectionController::class);
    // Gestione attributi di una collezione
    Route::post('collections/{collection}/attributes', [CollectionAttributeController::class, 'store'])->name('collection-attributes.store');
    Route::patch('collections/{collection}/attributes/{attribute}', [CollectionAttributeController::class, 'update'])->name('collection-attributes.update');
    Route::delete('collections/{collection}/attributes/{attribute}', [CollectionAttributeController::class, 'destroy'])->name('collection-attributes.destroy');

    Route::resource('locations', LocationController::class);
    Route::resource('tags', TagController::class);
    Route::resource('items', ItemController::class);

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
});

require __DIR__.'/auth.php';
