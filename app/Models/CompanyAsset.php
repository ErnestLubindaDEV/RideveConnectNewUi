<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAsset extends Model
{
     use HasFactory;

    protected $table = 'company_assets';

    protected $fillable = [
        'asset_name',
        'asset_number',
        'condition',
        'description',
        'purchase_date',
        'collection_date',
        'asset_type',
        'asset_cost',
        'assigned_by',
        'warranty_expiry',
        'assigned_to',
        'signature'
    ];
}
