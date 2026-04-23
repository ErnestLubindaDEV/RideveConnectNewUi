<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveApprovalWithPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveApplication;
    public $pdf;

    public function __construct($leaveApplication, $pdf)
    {
        $this->leaveApplication = $leaveApplication;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Leave Application Status')
            ->view('emails.leave-approval-with-pdf')
            ->attachData($this->pdf->output(), 'Leave_Application.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
