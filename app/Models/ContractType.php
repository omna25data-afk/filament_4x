<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: contract_types
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ContractType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name_ar', 'name_en', 'description', 'level', 'is_system_defined', 'is_active', 'display_order'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'is_system_defined' => 'integer', 'is_active' => 'integer', 'display_order' => 'integer'];

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'parent_id', 'id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'contract_type_id');
    }

    public function feeSettings()
    {
        return $this->hasMany(FeeSetting::class, 'contract_type_id');
    }

    public function fineSettings()
    {
        return $this->hasMany(FineSetting::class, 'contract_type_id');
    }
}
