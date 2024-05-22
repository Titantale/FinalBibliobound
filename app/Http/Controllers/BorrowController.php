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

class BorrowController extends Controller
{
    public function approval(){
        $borrows = Borrow::where('borrowstatus', 1)->get(); // Fetch all borrow records with borrowstatus equal to 1
        return view ('books.approval', ['borrows' => $borrows]);
    }

    public function borrowBook($id)
    {
        // Check if the user is authenticated and has userstatus equal to 1
        if(auth()->check() && auth()->user()->userstatus == 1) {
            // Count the number of books the user has borrowed
            $borrowedBooksCount = Borrow::where('user_id', auth()->id())
                                        ->whereIn('borrowstatus', [1, 2]) // Borrow statuses 1 and 2 indicate borrowed books
                                        ->count();

            // Check if the user has already borrowed 5 books
            if ($borrowedBooksCount >= 5) {
                // Redirect back with an error message
                return redirect()->back()->with('error', 'You have already borrowed the maximum number of books allowed.');
            }

            // Find the book by ID
            $book = Book::findOrFail($id);

            // Create a new borrow record
            Borrow::create([
                'user_id' => auth()->id(),
                'book_id' => $book->id,
                'username' => auth()->user()->name,
                'useremail' => auth()->user()->email,
                'borrowstatus' => 1, // Set borrowstatus to 1
            ]);

            // Update the status of the book to 2 (booked)
            $book->update(['status' => 2]);

            // Redirect back to the book page with a success message
            return redirect()->back()->with('success', 'Book booked successfully!');
        } else {
            // Redirect back if user is not authenticated or userstatus is not 1
            return redirect()->back()->with('error', 'You are not authorized to borrow this book.');
        }
    }

    public function approveBorrow($id)
    {
    // Find the borrow record by ID
    $borrow = Borrow::findOrFail($id);

    // Update borrow status to approved (2) and set approve time
    $borrow->update([
        'borrowstatus' => 2,
        'approvetime' => now(), // Assuming approvetime is a timestamp field
        'returndate' => Carbon::now()->addDays(7),
    ]);

    // Update the status of the corresponding book to borrowed (3)
    $borrow->book->update(['status' => 3]);

    // Redirect back with success message
    return redirect()->back()->with('success', 'Borrow request approved successfully!');
    }

    public function rejectBorrow(Request $request, $id)
    {
        // Validate the reason field
        $request->validate([
            'reason' => 'required',
        ]);

        // Find the borrow record by ID
        $borrow = Borrow::findOrFail($id);

        // Update borrow status to rejected (3) and set reject reason
        $borrow->update([
            'borrowstatus' => 3,
            'reason' => $request->input('reason'),
        ]);

        // Update the status of the corresponding book to available (1)
        $borrow->book->update(['status' => 1]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Borrow request rejected successfully!');
    }
}
