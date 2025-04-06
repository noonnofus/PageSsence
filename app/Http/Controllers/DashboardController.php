<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\UserBook;
use App\Models\Book;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $savedBooks = Book::whereIn('id', function ($query) use ($user) {
            $query->select('book_id')
                  ->from('user_books')
                  ->where('user_id', $user->id);
        })->get();

        return Inertia::render('Dashboard', [
            'savedBooks' => $savedBooks,
            'username' => $user->name,
        ]);
    }

}
