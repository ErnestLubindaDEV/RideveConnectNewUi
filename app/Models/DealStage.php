<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DealStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deal_stages';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'stage_id';

    /**
     * Indicates if the model should be timestamped.
     * Set based on whether 'created_at'/'updated_at' are managed by Eloquent.
     * The SQL schema only has 'created_at' with a default, no 'updated_at'.
     * If you want Eloquent to manage 'created_at', keep this true,
     * otherwise set to false if you rely solely on the DB default.
     * If you need only 'created_at', define the UPDATED_AT constant as null.
     *
     * @var bool
     */
    public $timestamps = true; // Assumes you want Eloquent to manage created_at

    /**
     * The name of the "updated at" column.
     * Set to null because the 'deal_stages' table schema only has 'created_at'.
     *
     * @var string|null
     */
    const UPDATED_AT = null;


    /**
     * The attributes that are mass assignable.
     * Usually deal stages are static, but enabling if needed.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stage_name',
        'description',
    ];

    /**
     * Define the relationship: A DealStage can have many Deals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals(): HasMany
    {
        // Args: Related model, foreign key on related table, local key on this table
        return $this->hasMany(Deal::class, 'stage_id', 'stage_id');
    }
}

?>