<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductProject extends Pivot
{
    protected $table = 'product_project';

    protected $fillable = [
        'project_id',
        'product_id',
        'quantity',
    ];
}
