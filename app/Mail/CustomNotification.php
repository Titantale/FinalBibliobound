<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $notificationMessage; // Rename from $message to avoid conflict

    /**
     * Create a new message instance.
     *
     * @param string $name The recipient's name.
     * @param string $notificationMessage The custom message.
     */
    public function __construct($name, $notificationMessage)
    {
        $this->name = $name;
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Custom Notification')
                    ->view('emails.custom_notification');
    }
}
