<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'show'])
    ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
});

Route::middleware(['auth'])->prefix('api')->group(function () {
    //search bar
    Route::get('/books/search', [BookController::class, 'findByName']);

    // filter by genre
    Route::get('/books/filter-by-genre', [BookController::class, 'filterByGenre']);

    Route::post('/book/create', [BookController::class, 'store']);

    Route::post('/book/save', [BookController::class, 'save']);

    Route::put('/book/update/{id}', [BookController::class, 'update']);

    Route::delete('/book/unsave/{book}', [BookController::class, 'unsave']);

    Route::get('/book/{id}/rating', [BookController::class, 'getAverageRating']);
    
    Route::post('/book/rate', [BookController::class, 'storeRate']);

    Route::post('/review/create', [BookController::class, 'storeReview']);
    
    // Review editing
    Route::put('/review/update/{id}', [BookController::class, 'editReview']);
    
    Route::get('/book/{id}/review', [BookController::class, 'getReviews']);

    Route::delete('/review/delete/{id}', [BookController::class, 'deleteReview']);
});


require __DIR__.'/auth.php';