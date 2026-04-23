<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'address',
        'category',
        'PACRA_Certificate',
        'ZRA_Taxclearance',
        'company_profile',
        'NAPSA_Complaince_certificate',
        'Bank_reference_letter',
        'status',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
