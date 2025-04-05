<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    // Searching book by book title
    public function findByName(Request $request) {
        $title = $request->query('title', '');
    
        $books = Book::when($title, function ($query, $title) {
            return $query->where('title', 'like', "%{$title}%");
        })->get();
    
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        // validate if the user is Admin
        $user = auth()->user();

        if ($user && $user->role === 'User') {
            return redirect('/');
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



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
