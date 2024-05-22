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

class RateController extends Controller
{
    public function rateandreview($id)
    {
        // Retrieve the book by its ID
        $book = Book::findOrFail($id);
        
        // Pass the book data to the view and return it
        return view('books.return', compact('book'));
    } 

    public function updaterate(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'rating' => 'required|numeric|between:0.5,5',
            'review' => 'required|string|max:255',
        ]);

        // Find the book
        $book = Book::findOrFail($id);

        // Find the borrow record for the current user and book
        $borrow = Borrow::where('book_id', $book->id)
            ->where('user_id', auth()->id())
            ->where('borrowstatus', 2)
            ->firstOrFail();

        // Update borrow record with rating and review
        $borrow->update([
            'rate' => $request->rating,
            'review' => $request->review,
            'actualreturndate' => now(),
            'borrowstatus' => 5, // Set borrowstatus to 5
        ]);

        // Update the corresponding book's status to 1

        // Get all borrow records for this book with borrowstatus 5
        $borrowRecords = Borrow::where('book_id', $book->id)
                                ->where('borrowstatus', 5)
                                ->get();

        // If there are no borrow records, set totalrating to null
        $totalRating = null;
        $averageRating = null;

        if (!$borrowRecords->isEmpty()) {
            // Calculate the total rating
            $totalRating = $borrowRecords->sum('rate');
            $averageRating = $totalRating / $borrowRecords->count();
        }

        // Update the book's totalrating
        $book->update([
            'status' => 1,
            'totalrating' => $averageRating,
        ]);

        // Redirect to the 'book-borrowed' route after successful return
        return redirect()->route('book-borrowed')->with('success', 'Rating and review submitted successfully!');
    }
}
