<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccidentHistory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'driver_name',
        'incident_date',
        'location',
        'description',
        'severity', // Minor, Moderate, Major, Totaled
        'police_report_number',
        'insurance_status', // Pending, Claimed, Rejected
        'estimated_repair_cost'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'registration_number');
    }
}
