<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'department',
        'priority',
        'needed_by',
        'requested_by',
        'purpose',
        'status', 
        'supervisor_signature',
    ];

    public function items()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    public function quotationFiles()
    {
        return $this->hasMany(QuotationFile::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

}
