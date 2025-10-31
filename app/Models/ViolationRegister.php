<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: violation_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ViolationRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'violation_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notary_id', 'violation_hijri_date', 'violation_type_ar', 'violation_details', 'recorded_by_user_id', 'action_taken'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'notary_id' => 'integer', 'violation_hijri_date' => 'date', 'recorded_by_user_id' => 'integer'];

    public function notary()
    {
        return $this->belongsTo(Notary::class, 'notary_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id', 'id');
    }
}
