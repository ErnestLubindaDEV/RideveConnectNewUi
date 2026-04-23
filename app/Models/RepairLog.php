<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RepairLog extends Model
{
    
    protected $fillable = [
        'vehicle_id', 
        'report_date', 
        'reported_by', 
        'repair_description', 
        'service_provider', 
        'cost', 
        'downtime_status', 
        'report', 
        'remarks',
        'completion_date' // This is the new column for the Accountant's use
    ];



    
    // This allows the table to show the Vehicle Name/Plate
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
