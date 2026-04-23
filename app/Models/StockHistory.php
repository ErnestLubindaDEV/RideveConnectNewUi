<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity_added', 'location','type', 'stock_deducted', 'added_by', 'grn_number', 'cost'  ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
