<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Communication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'communications';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'communication_id';

    /**
     * Indicates if the model should be timestamped.
     * The SQL schema only has 'created_at' with a default.
     *
     * @var bool
     */
    public $timestamps = true; // Manage 'created_at'

    /**
     * The name of the "updated at" column.
     * Set to null because the 'communications' table schema only has 'created_at'.
     *
     * @var string|null
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id', // Can be null if linked only to deal
        'deal_id', // Can be null if linked only to lead
        'communication_type',
        'subject',
        'body',
        'communication_date', // You might want Eloquent to manage this via created_at instead
        // 'user_id', // Add if you implement user tracking
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'communication_date' => 'datetime', // Cast to Carbon datetime object
    ];


    /**
     * Define the relationship: A Communication can belong to a Lead.
     * This relationship is optional (lead_id can be NULL).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead(): BelongsTo
    {
        // Args: Related model, foreign key on this table, owner key on related table
        return $this->belongsTo(Lead::class, 'lead_id', 'lead_id');
    }

    /**
     * Define the relationship: A Communication can belong to a Deal.
     * This relationship is optional (deal_id can be NULL).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deal(): BelongsTo
    {
        // Args: Related model, foreign key on this table, owner key on related table
        return $this->belongsTo(Deal::class, 'deal_id', 'deal_id');
    }

    // Optional: Add a relationship for the user who logged the communication if implemented
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
}

?>
