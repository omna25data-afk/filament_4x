<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: entries
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Entry extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'register_id', 'contract_type_id', 'writer_type_id', 'writer_user_id', 'writer_notary_id', 'writer_other_id', 'document_hijri_date', 'document_gregorian_date', 'document_paper_number', 'entry_status', 'certifier_user_id', 'certification_hijri_date', 'certification_gregorian_date', 'court_register_entry_number', 'court_register_page_number', 'court_register_number', 'court_box_number', 'delivery_hijri_date', 'delivery_gregorian_date', 'delivery_receipt_image_path', 'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'register_id' => 'integer', 'contract_type_id' => 'integer', 'writer_type_id' => 'integer', 'writer_user_id' => 'integer', 'writer_notary_id' => 'integer', 'writer_other_id' => 'integer', 'document_hijri_date' => 'date', 'document_gregorian_date' => 'date', 'certifier_user_id' => 'integer', 'certification_hijri_date' => 'date', 'certification_gregorian_date' => 'date', 'court_register_page_number' => 'integer', 'court_register_number' => 'integer', 'delivery_hijri_date' => 'date', 'delivery_gregorian_date' => 'date'];

    public function marriageContract()
    {
        return $this->hasMany(MarriageContract::class, 'entry_id');
    }

    public function agencyContract()
    {
        return $this->hasMany(AgencyContract::class, 'entry_id');
    }

    public function saleContract()
    {
        return $this->hasMany(SaleContract::class, 'entry_id');
    }

    public function divorceAttestation()
    {
        return $this->hasMany(DivorceAttestation::class, 'entry_id');
    }

    public function disposalContract()
    {
        return $this->hasMany(DisposalContract::class, 'entry_id');
    }

    public function partitionContract()
    {
        return $this->hasMany(PartitionContract::class, 'entry_id');
    }

    public function reconciliationAttestation()
    {
        return $this->hasMany(ReconciliationAttestation::class, 'entry_id');
    }

    public function financialData()
    {
        return $this->hasMany(EntryFinancialDatum::class, 'entry_id');
    }
}
