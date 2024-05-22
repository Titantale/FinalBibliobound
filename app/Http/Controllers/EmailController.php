<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\LateReturnNotification;
use App\Mail\AllLateReturnNotification;
use App\Mail\CustomNotification;


use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon; // Import Carbon library

class EmailController extends Controller
{
    public function sendNotification($id)
    {
        $borrow = Borrow::findOrFail($id);
        $user = $borrow->user;
        $bookName = $borrow->book->title;

        // Send email
        Mail::to($user->email)->send(new LateReturnNotification($user->name, $borrow->returndate,  $bookName));

        // Update borrow record to indicate notification sent and increase notification count
        $borrow->notification_count++;
        $borrow->save();

        return back()->with('success', 'Notification email sent successfully');
    }

    public function sendallnotification(Request $request)
    {
        // Fetch all late users
        $lateBorrowers = Borrow::whereDate('returndate', '<', now())
                                ->where('borrowstatus', '<>', 5)
                                ->get();

        // Loop through each late user and send email notification
        foreach ($lateBorrowers as $borrower) {
            // Send notification email to the late user
            Mail::to($borrower->useremail)->send(new AllLateReturnNotification($borrower->username, $borrower->returndate, $borrower->useremail, $borrower->book->title));

            $borrower->notification_count++;
            $borrower->save();
        }

        // Optionally, you can redirect back or return a response indicating the success
        return redirect()->back()->with('success', 'Email notifications sent to all late users.');
    }

    public function customnotification(Request $request)
    {
        $borrowId = $request->input('borrow_id');
        $borrow = Borrow::findOrFail($borrowId);
        $user = $borrow->user;
        $message = $request->input('message');

        // Send email with custom message
        Mail::to($user->email)->send(new CustomNotification($user->name, $message));

        // Update borrow record or any other necessary action
        // For example, increase notification count
        $borrow->notification_count++;
        $borrow->save();

        return back()->with('success', 'Custom notification email sent successfully');
    }
}
