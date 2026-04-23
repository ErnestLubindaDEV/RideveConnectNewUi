<?php

namespace app\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveApplication;

    protected $fillable = [
        'employee_name', 'employment_date', 'phone_number', 'emergency_contact',
        'leave_type', 'leave_duration', 'leave_from', 'leave_to', 'additional_notes',
        'contract_type', 'signature', 'supervisor_id'
    ];
    
    public function supervisor()
    {
        return $this->belongsTo(HRM::class, 'supervisor_id');
    }
    

    public function __construct($leaveApplication)
    {
        $this->leaveApplication = $leaveApplication;
    }

    public function build()
    {
        return $this->subject('New Leave Application Notification')
                    ->view('emails.leave_application')
                    ->with(['leaveApplication' => $this->leaveApplication]);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_name', 'employee_name');
    }
}
