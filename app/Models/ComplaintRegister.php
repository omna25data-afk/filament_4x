<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: complaint_registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ComplaintRegister extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'complaint_registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complainant_name_ar', 'complainant_contact', 'complaint_against_notary_id', 'complaint_hijri_date', 'complaint_details', 'status', 'handled_by_user_id', 'resolution_notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'complaint_against_notary_id' => 'integer', 'complaint_hijri_date' => 'date', 'handled_by_user_id' => 'integer'];
}
