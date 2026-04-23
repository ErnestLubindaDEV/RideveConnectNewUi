<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Deal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deals';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'deal_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'deal_name',
        'lead_id',
        'stage_id',
        'value',
        'currency',
        'expected_close_date',
        'actual_close_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2', // Cast 'value' to a decimal with 2 places
        'expected_close_date' => 'date', // Cast to Carbon date object
        'actual_close_date' => 'date',   // Cast to Carbon date object
    ];

    /**
     * Define the relationship: A Deal belongs to a Lead.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead(): BelongsTo
    {
        // Args: Related model, foreign key on this table, owner key on related table
        return $this->belongsTo(Lead::class, 'lead_id', 'lead_id');
    }

    /**
     * Define the relationship: A Deal belongs to a DealStage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stage(): BelongsTo
    {
        // Args: Related model, foreign key on this table, owner key on related table
        return $this->belongsTo(DealStage::class, 'stage_id', 'stage_id');
    }

    /**
     * Define the relationship: A Deal can have many Communications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function communications(): HasMany
    {
        // Args: Related model, foreign key on related table, local key on this table
        return $this->hasMany(Communication::class, 'deal_id', 'deal_id');
    }
}

?>