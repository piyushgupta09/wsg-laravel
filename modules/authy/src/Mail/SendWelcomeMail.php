<?php

namespace Fpaipl\Authy\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $subject;

    public function __construct(string $username)
    {
        $this->username = $username;
        $this->subject = 'Welcome to ' . config('app.name');
    }

    public function build()
    {
        return $this
            ->subject($this->subject)
            ->view('authy::emails.new_user_welcome')
            ->with([]);
    }
}
