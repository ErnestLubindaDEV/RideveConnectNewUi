<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'last_service_date',
        'last_service_mileage',
        'next_service_date',
        'next_service_mileage',
        'service_provider',
        'estimated_cost',
        'remarks', 
        'service_status',
        'updated_at',
        'vehicle_status'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}