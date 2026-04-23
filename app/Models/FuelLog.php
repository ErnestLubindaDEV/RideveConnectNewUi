<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelLog extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'fuel_type',
        'litres',
        'cost',
        'odometer_reading',
        'fuel_station',
        'driver',
        'km_per_litre',
        'remarks'
    ];

    /**
     * Get the vehicle associated with the fuel log.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'registration_number');
    }
}