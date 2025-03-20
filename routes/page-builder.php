<?php

use Illuminate\Support\Facades\Route;
use MSA\LaravelGrapes\Http\Controllers\PageController;
use MSA\LaravelGrapes\Http\Controllers\BlockController;

/*
|--------------------------------------------------------------------------
| Page-Builder Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are for page builder.
|
*/

// Importnant: not change routes names it will affect builder working with routes
Route::prefix(config('lg.routes.prefix'))->middleware(config('lg.routes.middleware'))->name('page-builder.')->group(function () {

    Route::get('/page-builder', [PageController::class, 'index'])->name('index');
    Route::get('/page-builder/all-pages', [PageController::class, 'allPages'])->name('page.all');
    Route::post('/page-builder/create-page', [PageController::class, 'store'])->name('page.store');
    Route::put('/page-builder/update-page/{id}', [PageController::class, 'update'])->name('page.update');
    Route::get('/page-builder/find-page/{id}', [PageController::class, 'show'])->name('page.find');
    Route::delete('/page-builder/delete-page/{id}', [PageController::class, 'destroy'])->name('page.delete');
    
    Route::put('/page-builder/update-page-content/{id}', [PageController::class, 'updateContent'])->name('update.page_content');

    Route::get('/page-builder/custome-components', [BlockController::class, 'allBlocks'])->name('custome_component.all');
    Route::post('/page-builder/custome-components/store', [BlockController::class, 'store'])->name('custome_component.store');
    Route::put('/page-builder/custome-components/update/{id}', [BlockController::class, 'update'])->name('custome_component.update');
    Route::delete('/page-builder/custome-components/delete/{id}', [BlockController::class, 'destroy'])->name('custome_component.delete');

    Route::get('testing', function() {
        dd(config('lg.dynamic_traits_model.users'));
    });

});
