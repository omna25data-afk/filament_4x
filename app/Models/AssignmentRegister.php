<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: assignment_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class AssignmentRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assignment_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assigned_notary_id', 'original_notary_id', 'reason', 'start_hijri_date', 'end_hijri_date', 'assigned_by_user_id', 'status', 'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'assigned_notary_id' => 'integer', 'original_notary_id' => 'integer', 'start_hijri_date' => 'date', 'end_hijri_date' => 'date', 'assigned_by_user_id' => 'integer'];
}
