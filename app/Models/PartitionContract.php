<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: partition_contracts
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class PartitionContract extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partition_contracts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id', 'deceased_name', 'heirs_details'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'entry_id' => 'integer'];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id', 'id');
    }
}
