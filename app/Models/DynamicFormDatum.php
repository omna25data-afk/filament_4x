<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: dynamic_form_data
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class DynamicFormDatum extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dynamic_form_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id', 'dynamic_form_field_id', 'value_text', 'value_number', 'value_date', 'value_json'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'entry_id' => 'integer', 'dynamic_form_field_id' => 'integer', 'value_number' => 'decimal:2', 'value_date' => 'date', 'value_json' => 'array'];
}
