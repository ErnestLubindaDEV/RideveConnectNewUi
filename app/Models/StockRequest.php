<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'products',
        'quantities',
        'status',
        'request_by',
        'approved_by', 
        'signature',
        'collecting_staff',
    ];

    protected $casts = [
   
        'products' => 'array',
        'quantities' => 'array',
    ];
}
