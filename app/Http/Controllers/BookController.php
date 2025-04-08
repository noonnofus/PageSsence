<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Book;
use App\Models\UserBook;
use App\Models\BookRate;
use App\Models\BookChat;

class BookController extends Controller
{
    public function index() {
        $user = Auth::user();

        Gate::authorize('viewAny', Book::class);

        $books = Book::latest()->take(10)->get();

        $allBook = Book::latest()->get();

        return Inertia::render('Books/All', [
            'user' => $user,
            'books' => $books,
            'allBook' => $allBook,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
    
    public function findByName(Request $request) {
        $title = $request->query('title', '');
    
        $books = Book::when($title, function ($query, $title) {
            return $query->where('title', 'like', "%{$title}%");
        })->get();
    
        return response()->json($books);
    }

    public function filterByGenre(Request $request)
    {
        $genre = $request->query('genre');

        $books = Book::when($genre, fn($query) =>
            $query->where('genre', $genre)
        )->get();

        return response()->json($books);
    }

    public function create() {
        Gate::authorize('viewAny', Book::class);

        return Inertia::render('Books/Create', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function store(Request $req)
    {
        // validate if the user is Admin
        $user = Auth::user();

        if ($user && $user->role === 'User') {
            return response()->json([
                'message' => 'Not allowed to create a book',
            ], 403);
        }
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $req->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string|max:200',
            'genre' => 'required|string|max:100',
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'publication_year' => ['required', 'regex:/^\d{4}$/'],
        ]);

        $validated['price'] = (float) $validated['price'];
        $validated['publication_year'] = (int) $validated['publication_year'];

        $book = Book::create($validated);

        return response()->json([
            'message' => 'Book created successfully!',
            'book' => $book
        ], 201);
    }

    public function save(Request $req)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $req->validate([
            'bookId' => 'required|exists:books,id',
        ]);

        $alreadySaved = UserBook::where('user_id', $user->id)
        ->where('book_id', $validated['bookId'])
        ->exists();

        if ($alreadySaved) {
            return response()->json(['message' => 'Already saved.'], 200);
        };

        UserBook::create([
            'user_id' => (int) $user->id,
            'book_id' => (int) $validated['bookId'],
        ]);

        return response()->json([
            'message' => 'Book saved successfully!',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'genre' => 'nullable|string',
            'publication_year' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return response()->json(['message' => 'Book updated successfully']);
    }

    public function unsave(Book $book)
    {
        $user = auth()->user();

        $user->savedBooks()->detach($book->id);

        return response()->json(['message' => 'Book unsaved']);
    }

    public function getAverageRating($id)
    {
        $book = Book::with('ratings')->findOrFail($id);
        $average = round($book->ratings->avg('rating'), 1);

        return response()->json([
            'average' => $average,
        ]);
    }
    
    public function getReviews($id)
    {
        $reviews = BookChat::where('book_id', (int)$id)->get();

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    public function storeRate(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        $userId = auth()->id();
    
        $existingRate = BookRate::where('user_id', $userId)
            ->where('book_id', $request->book_id)
            ->first();
    
        if ($existingRate) {
            return response()->json(['message' => 'You have already rated this book.'], 200);
        }
    
        BookRate::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'rating' => $request->rating,
        ]);
    
        return response()->json(['message' => 'Thanks for rating!']);
    }

    public function storeReview(Request $req)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $req->validate([
            'book_id' => 'required|exists:books,id',
            'message' => 'required|string|max:255',
        ]);

        $validated['book_id'] = (int) $validated['book_id'];
        $validated['user_id'] = $user->id;

        $book = BookChat::create($validated);

        return response()->json([
            'message' => 'Review created successfully!',
        ], 201);
    }

    public function editReview(Request $req, $id)
    {
        $review = BookChat::findOrFail($id);

        $validated = $req->validate([
            'id' => 'required|integer',
            'user_id' => 'required',
            'book_id' => 'required',
            'message' => 'required|string',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        $validated['user_id'] = (int) $validated['user_id'];
        $validated['book_id'] = (int) $validated['book_id'];

        $review->update($validated);

        return response()->json(['message' => 'Review updated successfully']);
    }

    public function deleteReview(Request $req, $id)
    {
        $review = BookChat::findOrFail($id);

        $review->delete();

        return response()->json(['message' => 'Review successfully deleted.']);
    }
}
