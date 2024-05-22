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

class MoodController extends Controller
{
    public function mood(){
        session()->forget('recommended_books');

        $books = Book::all(); // Fetch all books (or use any other criteria to fetch specific books)
        return view ('books.mood', ['books' => $books]);
    }

    public function recommendBook(Request $request)
    {
        // Retrieve the IDs of already recommended books from session
        $recommendedBookIds = session()->get('recommended_books', []);

        // Define genres corresponding to each mood
        $moodGenres = [
            0 => ['Romance'],
            1 => ['Mystery', 'Thriller'],
            2 => ['Fantasy'],
            3 => ['Historical', 'Fiction', 'Non-Fiction'],
            4 => ['Comedy', 'Young-Adult']
        ];

        // Get the selected mood from the request
        $selectedMood = $request->input('mood');

        // Store the selected mood in the session
        session()->put('selected_mood', $selectedMood);

        // Get genres corresponding to the selected mood
        $selectedGenres = $moodGenres[$selectedMood] ?? [];

        // Query for books with all of the selected genres excluding already recommended books
        $recommendedBooks = Book::where('status', 1) // Only available books
                                ->whereNotIn('id', $recommendedBookIds) // Exclude already recommended books
                                ->where(function($query) use ($selectedGenres) {
                                    foreach ($selectedGenres as $genre) {
                                        $query->where(function($query) use ($genre) {
                                            $query->where('genre1', $genre)
                                                ->orWhere('genre2', $genre)
                                                ->orWhere('genre3', $genre)
                                                ->orWhere('genre4', $genre);
                                        });
                                    }
                                })
                                ->inRandomOrder() // Randomize the results
                                ->take(3) // Take three books
                                ->get(); // Get the results

        // Check if any books were found
        if ($recommendedBooks->isNotEmpty()) {
            // Update recommended book IDs
            $recommendedBookIds = array_merge($recommendedBookIds, $recommendedBooks->pluck('id')->toArray());
            session()->put('recommended_books', $recommendedBookIds);

            // If books are found, redirect to the view to display the recommended books
            return view('books.recommend', ['books' => $recommendedBooks]);
        } else {
            // If no book is found, return an appropriate response (e.g., error message)
            return redirect()->route('book-mood')->with('message', 'All related books have been recommended.');
        }
    }

    public function nextRecommendation()
    {
        // Retrieve the selected mood from the session
        $selectedMood = session()->get('selected_mood');

        // Redirect to the recommend-book route with the selected mood
        return redirect()->route('recommend-book', ['mood' => $selectedMood]);
    }
}
