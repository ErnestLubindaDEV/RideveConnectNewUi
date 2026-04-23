<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'registration_number', 
        'make',                
        'model',               
        'engine_type',         
        'transmission',        
        'current_mileage',     
        'purchase_date',       
        'assigned_driver',     
        'status',              
        'service_status',      
    ];

   
    public function serviceSchedule(): HasOne
    {
        return $this->hasOne(ServiceSchedule::class, 'vehicle_id');
    }

    public function compliance() {
    return $this->hasOne(VehicleCompliance::class);
}
}