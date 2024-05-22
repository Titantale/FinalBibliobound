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

class ExtendController extends Controller 
{

    public function approveextend(){
        $borrows = Borrow::where('extendreq', 1)
                        ->where('borrowstatus', '<>', 5)
                        ->get(); // Fetch all borrow records with extendreq equal to 1
        return view ('books.extendapproval', ['extends' => $borrows]);
    }

    public function requestExtension($id, Request $request)
    {
        $borrow = Borrow::findOrFail($id);
        
        $newReturnDate = Carbon::createFromFormat('Y-m-d', $request->input('new_return_date'));
        $currentReturnDate = Carbon::parse($borrow->returndate);

        // Check if the new return date is greater than the current one
        if ($newReturnDate->greaterThan($currentReturnDate)) {
            $borrow->extendreq = 1;
            $borrow->extenddate = $newReturnDate;
            $borrow->reasonextend = $request->input('reasonextend'); // Add this line to set the reasonextend
            $borrow->save();

            return redirect()->back()->with('success', 'Extension request submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'Selected date must be greater than the current return date.');
        }
    }

    public function extendapprove($id)
    {
        // Find the borrow record by ID
        $borrow = Borrow::findOrFail($id);

        // Update borrow status to approved (2), set returndate to extenddate, and reset extendreq
        $borrow->update([
            'returndate' => $borrow->extenddate,
            'extendreq' => 2,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Extend request approved successfully!');
    }

    public function extendreject(Request $request, $id)
    {
        // Validate the reason field
        $request->validate([
            'rejectreason' => 'required',
        ]);

        // Find the borrow record by ID
        $borrow = Borrow::findOrFail($id);

        // Update borrow status to rejected (3), set reject reason, and reset extendreq
        $borrow->update([
            'rejectextend' => $request->input('rejectreason'),
            'extendreq' => 0,
        ]);

        // Redirect back to the approval page
        return redirect()->back()->with('success', 'Extend request rejected successfully!');
    }
}
