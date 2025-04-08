<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/books', [BookController::class, 'index']);

// Route::get('/books/search', [BookController::class, 'findByName']);

Route::get('/book/{id}', [BookController::class, 'show']); 

Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);