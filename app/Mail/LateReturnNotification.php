<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User; // Import the User model
use App\Models\Book; // Import the Book model
use App\Models\Borrow; // Import the Borrow model


class LateReturnNotification extends Mailable
{
    use Queueable, SerializesModels; 

    public $username;
    public $returnDate;
    public $bookName;

    /**
     * Create a new message instance.
     *
     * @param string $username
     * @param string $returnDate
     */
    public function __construct($username, $returnDate, $bookName)
    {
        $this->username = $username;
        $this->returnDate = $returnDate;
        $this->bookName = $bookName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $users = User::where('userstatus', 2)->pluck('email')->toArray();

        return $this->subject('Late Return Notification')
                    ->view('emails.late_return_notification')
                    ->cc($users);
    }
}
