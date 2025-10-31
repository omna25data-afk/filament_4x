<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: evaluation_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class EvaluationRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evaluation_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notary_id', 'evaluation_period_ar', 'evaluation_hijri_date', 'performance_score', 'evaluation_notes', 'evaluated_by_user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'notary_id' => 'integer', 'evaluation_hijri_date' => 'date', 'performance_score' => 'integer', 'evaluated_by_user_id' => 'integer'];
}
