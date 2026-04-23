<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class MemoMail extends Mailable
{
    public $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Memo Notification')
                    ->view('emails.memo')  // Optionally create a view for the email body
                    ->attach($this->filePath, [
                        'mime' => 'application/pdf',
                    ]);
    }
}
