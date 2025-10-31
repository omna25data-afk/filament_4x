<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: external_request_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ExternalRequestRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'external_request_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type_ar', 'requester_name_ar', 'requester_contact', 'requester_identity_type', 'requester_identity_number', 'related_entry_id', 'request_hijri_date', 'status', 'processed_by_user_id', 'processed_hijri_date', 'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'related_entry_id' => 'integer', 'request_hijri_date' => 'date', 'processed_by_user_id' => 'integer', 'processed_hijri_date' => 'date'];
}
