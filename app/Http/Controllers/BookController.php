<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\LateReturnNotification;
use App\Mail\AllLateReturnNotification;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon; // Import Carbon library
use Illuminate\Pagination\LengthAwarePaginator;



class BookController extends Controller
{


    //ViewAll
    public function index(Request $request){
        
        if ($request->has('query')) {
            // Perform search based on the title
            $query = $request->input('query');
            $books = Book::where('title', 'like', '%' . $query . '%')->paginate(3)->appends(['query' => $query]);
        } else {
            // If no search query, retrieve all books
            $books = Book::paginate(6);
        }

        $hasBorrowedBooks = false;
        if (auth()->check()) {
            $user = auth()->user();
            $hasBorrowedBooks = Borrow::where('user_id', $user->id)
                                    ->where('borrowstatus', 5)
                                    ->exists();
        }


    return view('books.all', [
        'books' => $books,
        'hasBorrowedBooks' => $hasBorrowedBooks

    ]);
    }


    //Search    
    public function search(Request $request) {
        // Get the search query from the form input
        $query = $request->input('query');
    
        // Perform the search using the Book model
        $books = Book::where('title', 'like', '%' . $query . '%')->paginate(3);

        $hasBorrowedBooks = false;
        if (auth()->check()) {
            $user = auth()->user();
            $hasBorrowedBooks = Borrow::where('user_id', $user->id)
                                    ->where('borrowstatus', 5)
                                    ->exists();
        }
    
        // Return the search results to the view
        return view('books.all', ['books' => $books, 'query' => $query, 'hasBorrowedBooks' => $hasBorrowedBooks]);
    }
    
    //ViewEdit
    public function edit($id){

        $book = Book::findOrFail($id);

        return view ('books.edit', ['book' => $book]);
    }

    //Update
    public function update(Request $request, $id){
        
        $book = Book::findOrFail($id);
    
        $book->title = $request->input('title'); // Using input() method
        $book->isbn = $request->input('isbn'); 
        $book->author = $request->input('author');
        $book->synopsis = $request->input('synopsis');
        $book->genre1 = $request->input('genre1');
        $book->genre2 = $request->input('genre2');
        $book->genre3 = $request->input('genre3');
        $book->genre4 = $request->input('genre4');
        $book->location = $request->input('location');

        // Handle image update
    if ($request->hasFile('image')) {
        // Validate and store the new image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $imageName = time() . '.' . $request->file('image')->extension();
        $request->file('image')->move(public_path('images'), $imageName);

        // Delete the previous image file
        if ($book->image) {
            unlink(public_path('images/' . $book->image));
        }

        // Update book image
        $book->image = $imageName;
    }

    
        $book->save();
    
        return back()->with('success', 'Book has been updated');
    }

    //ViewCreate
    public function create(){
        return view ('books.create');
    }

    //StoreNewCreate
    public function store(Request $request){

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        $imageName = null;
    if($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'.'.$image->extension();  
        $image->move(public_path('images'), $imageName);
    }

        $book = Book::create([
            'title'=>$request->title,
            'isbn'=>$request->isbn,
            'location'=>$request->location,
            'author'=>$request->author,
            'status'=>$request->status,
            'genre1'=>$request->genre1,
            'genre2'=>$request->genre2,
            'genre3'=>$request->genre3,
            'genre4'=>$request->genre4,
            'synopsis'=>$request->synopsis,
            'image' => $imageName     
        ]);

        return redirect()->route('book-listing')->with('success', 'New Book Has Been Added');
    }
    
    public function destroy($id){

        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('book-listing')->with('success', 'The Book Has Been Deleted');
    }

    //ViewSingle
    public function show($id){
        $book = Book::find($id);
    
        // Retrieve comments for the book with borrow status 5
        $comments = Borrow::where('book_id', $id)
                          ->where('borrowstatus', 5)
                          ->with('user') // Eager load the user relationship
                          ->get();
    
        return view ('books.single', ['book' => $book, 'comments' => $comments]);
    }
    

    
    

    public function borrowed(Request $request){
        $user = auth()->user();
        $query = Borrow::where('borrowstatus', 2);

        // Apply filters if present
        if ($request->has('name') && $request->name != '') {
            $query->where('username', 'like', '%' . $request->name . '%');
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'late') {
                $query->where('returndate', '<', now())
                      ->where('extendreq', '!=', 1);
            } elseif ($request->status == 'on-time') {
                $query->where('returndate', '>=', now());
            } elseif ($request->status == 'waiting') {
                $query->where('extendreq', 1);
            }
        }

        if ($user->userstatus == 1) {
            $query->where('username', $user->name);
        }

        $borrowedBooks = $query->get();

        return view('books.borrow', [
            'borrowedBooks' => $borrowedBooks,
            'filters' => $request->only('name', 'status')
        ]);
    }


    public function pending(){
        $user = auth()->user();
        $pendingRequests = Borrow::where('borrowstatus', 1)
                                ->where('username', $user->name)
                                ->get();
        return view ('books.pending', [
            'pendingRequests' => $pendingRequests
        ]);
    }


    public function rejected(){
        $user = auth()->user();
        if ($user->userstatus == 1) {
            $rejectedRequests = Borrow::where('borrowstatus', 3)
                                      ->where('username', $user->name)
                                      ->get();
            $rejectedExtendRequests = Borrow::where('extendreq', 0)
                                            ->where('username', $user->name)
                                            ->get();
        } elseif ($user->userstatus == 2) {
            $rejectedRequests = Borrow::where('borrowstatus', 3)
                                      ->get();
            $rejectedExtendRequests = Borrow::where('extendreq', 0)
                                            ->get();
        }                        
    
        return view ('books.rejected', [
            'rejectedRequests' => $rejectedRequests,
            'rejectedExtendRequests' => $rejectedExtendRequests
        ]);
    }
    

    public function history(Request $request){
        $user = auth()->user();
        $query = Borrow::where('borrowstatus', 5);
    
        // Apply filters if present and userstatus equals 2
        if ($user->userstatus == 2) {
            if ($request->has('name') && $request->name != '') {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                });
            }

            if ($request->has('status') && $request->status != '') {
                if ($request->status == 'late') {
                    $query->whereRaw('TIMESTAMPDIFF(DAY, returndate, actualreturndate) > 0');
                } elseif ($request->status == 'on-time') {
                    $query->whereRaw('TIMESTAMPDIFF(DAY, returndate, actualreturndate) <= 0');
                }
            }
        }

    

        if ($user->userstatus == 1) {           
            $query->where('user_id', $user->id);
        }
    
        $requesthistory = $query->get();
    
        return view('books.history', [
            'requesthistory' => $requesthistory,
            'filters' => $request->only('name', 'status') // Pass filters to the view
        ]);
    }
    
    


public function notify(){
    $user = auth()->user();
    
        $borrowedBooks = Borrow::where('borrowstatus', 2)
                        ->whereDate('returndate', '<', now()) // Filter out return dates in the past
                        ->get();

    return view ('books.borrow', [
        'borrowedBooks' => $borrowedBooks
    ]);
}

public function homeindex()
{
    $user = auth()->user();
    $username = $user->name;
    $userstatus = $user->userstatus;

    // Books Available: status = 1
    $booksAvailable = Book::where('status', 1)->count();

    // Calculate return status
    $now = Carbon::now();

    // If user status is 1, consider username in return calculations
    if ($userstatus == 1) {
        $onTimeReturnsQuery = Borrow::where('borrowstatus', 2)
                                    ->where('username', $username);
        $lateReturnsQuery = Borrow::where('borrowstatus', 2)
                                  ->where('username', $username);
    } else {
        $onTimeReturnsQuery = Borrow::where('borrowstatus', 2);
        $lateReturnsQuery = Borrow::where('borrowstatus', 2);
    }

    $onTimeReturns = $onTimeReturnsQuery->where('returndate', '>=', $now)->count();
    $lateReturns = $lateReturnsQuery->where('returndate', '<', $now)->count();

    // Books Booked
    $booksBooked = ($userstatus == 2) ? Book::where('status', 2)->count() : Borrow::where('username', $username)->where('borrowstatus', 1)->count();

    // Currently Borrow
    $currentlyBorrow = ($userstatus == 2) ? Book::where('status', 3)->count() : Borrow::where('username', $username)->where('borrowstatus', 2)->count();

    // Books Borrowed
    $booksBorrowed = ($userstatus == 2) ? Borrow::where('borrowstatus', 5)->count() : Borrow::where('username', $username)->where('borrowstatus', 5)->count();

    $topBooks = Book::orderBy('totalrating', 'desc')->take(5)->get();

    return view('home', compact(
        'booksAvailable', 
        'booksBooked', 
        'currentlyBorrow', 
        'booksBorrowed', 
        'onTimeReturns', 
        'lateReturns',
        'topBooks'
    ));
}




public function generateRecommendations()
{
    $user = auth()->user();

    // Get all borrows with ratings by the user
    $userBorrows = Borrow::where('user_id', $user->id)
        ->whereNotNull('rate')
        ->get(); // Retrieve a collection of borrow records

    // Initialize genre scores
    $genreScores = [
        'Romance' => 0,
        'Mystery' => 0,
        'Thriller' => 0,
        'Fantasy' => 0,
        'Historical' => 0,
        'Fiction' => 0,
        'Non-Fiction' => 0,
        'Comedy' => 0,
        'Young-Adult' => 0,
        'Horror' => 0,
        // Add other genres here as needed
    ];

    // Calculate genre scores based on user's ratings
    foreach ($userBorrows as $borrow) {
        $book = $borrow->book;
        $rating = $borrow->rate;

        foreach (['genre1', 'genre2', 'genre3', 'genre4'] as $genreField) {
            $genre = $book->$genreField;
            if (!empty($genre) && $genre !== '-' && isset($genreScores[$genre])) {
                $genreScores[$genre] += $rating;
            }
        }
    }

    // Calculate genre scores and sort them
    arsort($genreScores);

    // Initialize recommendations
    $recommendations = collect();

    // Array to store IDs of recommended books
    $recommendedBookIds = [];

    // Loop through each genre combination
    foreach ($genreScores as $genre => $score) {
        $genreBooks = Book::where(function($query) use ($genre) {
                $query->where('genre1', $genre)
                      ->orWhere('genre2', $genre)
                      ->orWhere('genre3', $genre)
                      ->orWhere('genre4', $genre);
            })
            ->whereNotIn('id', $userBorrows->pluck('book_id')->toArray()) // Exclude books the user has already seen
            ->orderByDesc('totalrating')
            ->get(); // Do not paginate here

        if ($genreBooks->isNotEmpty()) {
            // Add unique recommended book IDs to the array
            foreach ($genreBooks as $book) {
                if (!in_array($book->id, $recommendedBookIds)) {
                    $recommendedBookIds[] = $book->id;
                    $recommendations->push($book); // Add the book to recommendations
                }
            }
        }
    }

    return view('books.newrecomendation', ['recommendations' => $recommendations]);
}










   
}
