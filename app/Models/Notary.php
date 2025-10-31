<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: notaries
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Notary extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notaries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name_ar', 'second_name_ar', 'third_name_ar', 'fourth_name_ar', 'birth_place_governorate_id', 'birth_place_directorate_id', 'birth_place_sub_district_id', 'birth_place_village_id', 'birth_date', 'home_phone', 'address', 'qualification', 'job', 'workplace', 'functional_status', 'stop_reason', 'stop_date', 'notes', 'م', 'الاسم الأول', 'الإسم الثاني', 'الإسم الثالث', 'الإسم الرابع', 'اللقب', 'محل الميلاد', 'تاريخ الميلاد', 'نوع الهوية', 'رقم الهوية', 'تاريخ الإصدار', 'جهة الإصدار', 'العمل', 'جهة العمل', 'المؤهل', 'العنوان', 'قرى مناطق الإختصاص', 'عزلة مناطق الإختصاص', 'رقم التلفون', 'رقم القرار الوزاري', 'تاريخ القرار', 'رقم بطاقة الترخيص', 'تاريخ الترخيص', 'تاريخ إنتهاء أول ترخيص', 'رقم البطاقة الإلكترونية', 'تاريخ إصدار البطاقة', 'تاريخ إنتهاء أول بطاقة'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'birth_place_governorate_id' => 'integer', 'birth_place_directorate_id' => 'integer', 'birth_place_sub_district_id' => 'integer', 'birth_place_village_id' => 'integer', 'birth_date' => 'date', 'stop_date' => 'date', 'م' => 'integer', 'رقم الهوية' => 'integer', 'رقم التلفون' => 'integer', 'رقم القرار الوزاري' => 'integer', 'رقم بطاقة الترخيص' => 'integer', 'رقم البطاقة الإلكترونية' => 'integer'];

    public function entriesAsNotary()
    {
        return $this->hasMany(Entry::class, 'notary_id');
    }

    public function registers()
    {
        return $this->hasMany(Register::class, 'notary_id');
    }
}
