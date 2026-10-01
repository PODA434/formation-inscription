<?php
// À AJOUTER dans routes/web.php

use App\Http\Controllers\AdminInscriptionController;
use App\Http\Controllers\InscriptionController;
use App\Http\Middleware\AdminBasicAuth;
use Illuminate\Support\Facades\Route;

// Côté participant
Route::get('/inscription', [InscriptionController::class, 'create'])->name('inscription.create');
Route::post('/inscription', [InscriptionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('inscription.store');
Route::get('/inscription/statut/{inscription:reference}', [InscriptionController::class, 'statut'])
    ->name('inscription.statut');

// Côté organisateur (protégé par identifiant + mot de passe)
Route::middleware([AdminBasicAuth::class, 'throttle:60,1'])
    ->prefix('admin/inscriptions')
    ->name('admin.inscriptions.')
    ->group(function () {
        Route::get('/', [AdminInscriptionController::class, 'index'])->name('index');
        Route::get('/export', [AdminInscriptionController::class, 'export'])->name('export');
        Route::post('/{inscription:reference}/valider', [AdminInscriptionController::class, 'valider'])->name('valider');
        Route::post('/{inscription:reference}/refuser', [AdminInscriptionController::class, 'refuser'])->name('refuser');
    });
