<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: entry_financial_data
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class EntryFinancialDatum extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entry_financial_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_id', 'base_fees_amount', 'local_revenue_amount', 'revenue_incentive_amount', 'court_preparation_amount', 'support_amount', 'judiciary_support_amount', 'documentation_development_incentive_amount', 'sustainability_fee_amount', 'fine_amount', 'tax_amount', 'zakat_amount', 'total_collected_amount', 'fees_voucher_number', 'fees_voucher_date', 'tax_voucher_number', 'tax_voucher_date', 'zakat_voucher_number', 'zakat_voucher_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'entry_id' => 'integer', 'base_fees_amount' => 'decimal:2', 'local_revenue_amount' => 'decimal:2', 'revenue_incentive_amount' => 'decimal:2', 'court_preparation_amount' => 'decimal:2', 'support_amount' => 'decimal:2', 'judiciary_support_amount' => 'decimal:2', 'documentation_development_incentive_amount' => 'decimal:2', 'sustainability_fee_amount' => 'decimal:2', 'fine_amount' => 'decimal:2', 'tax_amount' => 'decimal:2', 'zakat_amount' => 'decimal:2', 'total_collected_amount' => 'decimal:2', 'fees_voucher_date' => 'date', 'tax_voucher_date' => 'date', 'zakat_voucher_date' => 'date'];

    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id', 'id');
    }
}
