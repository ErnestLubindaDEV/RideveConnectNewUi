<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\LeaveApplication;

class LeaveApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leave;

    public function __construct(LeaveApplication $leave)
    {
        $this->leave = $leave;
    }

    public function build()
    {
        return $this->subject('Leave Application Approved')
                    ->view('hrm.leave_approval');
    }
}
