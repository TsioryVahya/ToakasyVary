<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\StockProduitsFinisController;

// Rediriger la racine vers la page de connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// Route pour la page d'accueil (accessible après connexion)
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Routes d'authentification personnalisées
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/commandesPreview/preview', [CommandeController::class, 'previewForm'])->name('commandes.preview');
Route::post('/commandesPreview/preview', [CommandeController::class, 'preview'])->name('commandes.preview');
Route::post('/commandesPreview', [CommandeController::class, 'store'])->name('commandes.store');
Route::get('/stockProduitsFinis/all', [StockProduitsFinisController::class, 'showAllStocks'])->name('stockProduitsFinis.all');
/*
// Routes d'authentification de Breeze
Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
*/