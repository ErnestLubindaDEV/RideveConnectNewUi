<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VehicleCompliance extends Model
{
    protected $table = 'vehicle_compliances';

protected $fillable = [
    'vehicle_id',
    'insurance_provider',
    'insurance_policy_number',
    'insurance_expiry_date',
    'road_tax_expiry',
    'fitness_certificate_expiry',
    'compliance_status',
    'reminder_sent'
];

public function vehicle()
{
    return $this->belongsTo(Vehicle::class);
}

protected function calculatedStatus(): Attribute
{
    return Attribute::make(
        get: function () {
            $today = Carbon::today();
            
            // Collect all expiry dates into an array
            $dates = [
                $this->insurance_expiry_date ? Carbon::parse($this->insurance_expiry_date) : null,
                $this->road_tax_expiry ? Carbon::parse($this->road_tax_expiry) : null,
                $this->fitness_certificate_expiry ? Carbon::parse($this->fitness_certificate_expiry) : null,
            ];

            // Filter out nulls and find the earliest date (the one closest to expiring or already expired)
            $earliestDate = collect($dates)->filter()->min();

            if (!$earliestDate) {
                return 'No Data';
            }

            // 1. Check if already passed
            if ($earliestDate->isPast()) {
                return 'Expired';
            }

            // 2. Check if it is within the next 30 days (Pending)
            if ($earliestDate->diffInDays($today) < 30) {
                return 'Pending';
            }

            // 3. Otherwise, it's valid
            return 'Valid';
        },
    );
}

}
