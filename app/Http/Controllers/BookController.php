<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;


class BookController extends Controller
{

    public function index(Request $request){
        
        if ($request->has('query')) {
            // Perform search based on the title
            $query = $request->input('query');
            $books = Book::where('title', 'like', '%' . $query . '%')->paginate(3)->appends(['query' => $query]);
        } else {
            // If no search query, retrieve all books
            $books = Book::paginate(3);
        }

        return view ('books.all', ['books' => $books]);
    }

    public function search(Request $request) {
        // Get the search query from the form input
        $query = $request->input('query');
    
        // Perform the search using the Book model
        $books = Book::where('title', 'like', '%' . $query . '%')->paginate(3);
    
        // Return the search results to the view
        return view('books.all', ['books' => $books, 'query' => $query]);
    }
    

    public function edit($id){

        $book = Book::findOrFail($id);


        return view ('books.edit', ['book' => $book]);
    }

    public function update(Request $request, $id){
        
        $book = Book::findOrFail($id);
    
        $book->title = $request->input('title'); // Using input() method
        $book->isbn = $request->input('isbn'); 
        $book->author = $request->input('author');
        $book->status = $request->input('status');
        $book->synopsis = $request->input('synopsis');
        $book->genre1 = $request->input('genre1');
        $book->genre2 = $request->input('genre2');
        $book->genre3 = $request->input('genre3');
        $book->genre4 = $request->input('genre4');

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

    public function create(){
        return view ('books.create');
    }

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

    public function show($id){
        
        $book = Book::find($id);

        return view ('books.single', ['book' => $book]);
    }

    public function approval(){
        $books = Book::all(); // Fetch all books (or use any other criteria to fetch specific books)
        return view ('books.approval', ['books' => $books]);
    }

    public function mybook(){
        $books = Book::all(); // Fetch all books (or use any other criteria to fetch specific books)
        return view ('books.mybooks', ['books' => $books]);
    }

    public function mood(){
        $books = Book::all(); // Fetch all books (or use any other criteria to fetch specific books)
        return view ('books.mood', ['books' => $books]);
    }
    
}
