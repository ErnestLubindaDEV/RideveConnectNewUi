<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'employment_date',
        'phone_number',
        'emergency_contact',
        'leave_type',
        'leave_duration',
        'leave_from',
        'leave_to',
        'additional_notes',
        'contract_type',
        'employee_signature',
        'supervisor_name',
        'status',
        'supervisor_signature',
        'hr_signature',
    ];

    public function employee()
    {
        return $this->belongsTo(HRM::class, 'full_name', 'full_name'); 
    }
}