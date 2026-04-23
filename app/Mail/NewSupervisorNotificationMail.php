<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSupervisorNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $supervisorName;
    public $departmentName;

    /**
     * Create a new message instance.
     */
    public function __construct($supervisorName, $departmentName)
    {
        $this->supervisorName = $supervisorName;
        $this->departmentName = $departmentName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Supervisor Role')
                    ->view('emails.new_supervisor_notification')
                    ->with([
                        'supervisorName' => $this->supervisorName,
                        'departmentName' => $this->departmentName
                    ]);
    }
}
