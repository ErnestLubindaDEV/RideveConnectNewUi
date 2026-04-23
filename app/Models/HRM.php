<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HRM extends Model
{
    // Specify the table name
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class, 'employee_id', 'employee_id');
    }

   protected $fillable = [
    'employee_id',
    'full_name', 
    'phone_number', 
    'email', 
    'NRC', 
    'license_number', 
    'dob', 
    'gender', // <--- Add this line
    'nationality', 
    'department', 
    'position', 
    'contract_type', 
    'start_date', 
    'address',
    'national_id',
    'driver_license', 
    'user_id',
    'department_id',
    'leave_days',
];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
}
