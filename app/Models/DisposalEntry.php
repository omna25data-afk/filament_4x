<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: قيود_التصرفات
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class DisposalEntry extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'قيود_التصرفات';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'notary_register_number', 'notary_page_number', 'notary_entry_number', 'voucher_number', 'sustainability_amount', 'support_amount', 'fees_amount', 'certification_date', 'certification_hijri_date', 'sub_box_number', 'box_number', 'court_register_number', 'court_page_number', 'court_entry_number', 'document_date', 'other_writer_name', 'notary_writer_name', 'disposal_type', 'disposer_for_name', 'disposer_name', 'contract_type', 'entry_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}
