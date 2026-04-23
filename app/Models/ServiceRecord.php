<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
  protected $fillable = [
    'vehicle_id', 
    'service_provider', 
    'service_type', 
    'next_service_date', 
    'cost', 
    'status'
];

public function vehicle() {
    return $this->belongsTo(Vehicle::class);
}
}
