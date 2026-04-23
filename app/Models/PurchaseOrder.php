<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'vendor_id',
        'quotation_1_path',
        'quotation_2_path',
        'officer_name',
        'procurement_signature',  
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
