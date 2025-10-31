<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: registers
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Register extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'registers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'register_type_id', 'name', 'number', 'hijri_year', 'gregorian_year', 'page_count', 'entries_per_page', 'first_entry_serial_in_register', 'last_entry_serial_in_register', 'assigned_notary_id', 'owner_type', 'owner_id', 'opening_minutes_date', 'closing_minutes_date', 'status', 'created_by_user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'register_type_id' => 'integer', 'number' => 'integer', 'hijri_year' => 'integer', 'gregorian_year' => 'integer', 'page_count' => 'integer', 'entries_per_page' => 'integer', 'first_entry_serial_in_register' => 'integer', 'last_entry_serial_in_register' => 'integer', 'assigned_notary_id' => 'integer', 'owner_id' => 'integer', 'opening_minutes_date' => 'date', 'closing_minutes_date' => 'date', 'created_by_user_id' => 'integer'];

    public function entries()
    {
        return $this->hasMany(Entry::class, 'register_id');
    }
}
