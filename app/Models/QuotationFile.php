<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'requisition_id',
        'file_path',
        'file_number',
        'uploaded_at'
    ];

    protected $dates = ['uploaded_at'];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }
}
