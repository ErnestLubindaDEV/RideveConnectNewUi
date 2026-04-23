<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialRequestItem extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
    ];

    // Relationship to the MaterialRequest
    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    // Relationship to the Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
