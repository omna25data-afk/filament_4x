<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: reconciliation_attestations
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ReconciliationAttestation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reconciliation_attestations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id', 'husband_name', 'wife_name'
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
