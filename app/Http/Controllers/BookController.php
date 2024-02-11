<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter');

        $books = Book::when(
            $title, 
            fn($query,$title)
            =>$query
            ->title($title));

        $books = match($filter)
        {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount(),
        };
        $cacheKey = 'books:' . $filter . ':' . $title;

        $books = 
        // cache()->remember($cacheKey, 3600 , fn()=> 
        $books->get();
    // ); 

        return view('books.index', ['books'=>$books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Book $book)
    {
        $validate = $request->validate([
            'title' => 'required|string|min:5',
            'author' => 'required|string|min:5',
        ]);

        $book->create($validate);

        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // cache()->forget('book:' . $book->id);
        $cacheKey = 'book:'.$id;
        
        $book = 
        
        cache()->remember($cacheKey, 3600 , fn()=> 
        
        Book::with([
            'reviews'=>fn($query)=> $query->latest()
        ])->withAvgRating()->withReviewsCount()->findOrFail($id)
    );
      
        return view('books.show',['book' => $book]);
        // return view('books.show',['book' =>$book->load(['reviews'=>fn($query)=> $query->latest()])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
