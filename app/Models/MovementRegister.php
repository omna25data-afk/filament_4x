<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: movement_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MovementRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movement_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester_name_ar', 'requester_identity_type', 'requester_identity_number', 'requester_identity_issue_date', 'requester_identity_issuing_authority', 'requester_status', 'movement_reason', 'movement_hijri_date', 'movement_gregorian_date', 'assigned_notary_id', 'assigned_by_user_id', 'status', 'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'requester_identity_issue_date' => 'date', 'movement_hijri_date' => 'date', 'movement_gregorian_date' => 'date', 'assigned_notary_id' => 'integer', 'assigned_by_user_id' => 'integer'];
}
