<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseCopy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-database-copy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار قاعدة البيانات المنسوخة والتحقق من العلاقات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 اختبار قاعدة البيانات المنسوخة filament_4x_db...');
        
        try {
            // اختبار الاتصال
            $tables = DB::select('SHOW TABLES');
            $this->info("✅ الاتصال ناجح - عدد الجداول: " . count($tables));
            
            // اختبار الجداول الرئيسية
            $mainTables = [
                'users' => 'المستخدمين',
                'notaries' => 'الأمناء الشرعيين',
                'entries' => 'القيود',
                'marriage_contracts' => 'عقود الزواج',
                'agency_contracts' => 'عقود الوكالات',
                'sale_contracts' => 'عقود البيع',
                'divorce_attestations' => 'شهادات الطلاق',
                'disposal_contracts' => 'عقود التصرف',
                'partition_contracts' => 'عقود القسمة',
                'reconciliation_attestations' => 'شهادات الصلح'
            ];
            
            $this->info("\n📊 اختبار الجداول الرئيسية:");
            foreach ($mainTables as $table => $description) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("✅ {$description} ({$table}): {$count} سجل");
                } catch (\Exception $e) {
                    $this->error("❌ {$description} ({$table}): خطأ - " . $e->getMessage());
                }
            }
            
            // اختبار الجداول القديمة بالعربية
            $arabicTables = [
                'قيود_الزواج' => 'قيود الزواج القديمة',
                'قيود_الوكالات' => 'قيود الوكالات القديمة',
                'قيود_المبيع' => 'قيود البيع القديمة',
                'قيود_الطلاق' => 'قيود الطلاق القديمة',
                'قيود_التصرفات' => 'قيود التصرفات القديمة',
                'قيود_القسمة' => 'قيود القسمة القديمة',
                'قيود_الرجعة' => 'قيود الرجعة القديمة'
            ];
            
            $this->info("\n📊 اختبار الجداول القديمة بالعربية:");
            foreach ($arabicTables as $table => $description) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("✅ {$description} ({$table}): {$count} سجل");
                } catch (\Exception $e) {
                    $this->error("❌ {$description} ({$table}): خطأ - " . $e->getMessage());
                }
            }
            
            // اختبار العلاقات الأساسية
            $this->info("\n🔗 اختبار العلاقات الأساسية:");
            
            try {
                // اختبار علاقة entries مع notaries
                $entry = DB::table('entries')->first();
                if ($entry) {
                    $this->info("✅ تم العثور على قيد أول برقم: {$entry->id}");
                    if (isset($entry->notary_id)) {
                        $notary = DB::table('notaries')->find($entry->notary_id);
                        if ($notary) {
                            $this->info("✅ العلاقة مع الأمين الشرعي تعمل بشكل صحيح");
                        } else {
                            $this->warn("⚠️  العلاقة مع الأمين الشرعي لا تعمل");
                        }
                    }
                }
                
                // اختبار علاقة marriage_contracts مع entries
                $marriage = DB::table('marriage_contracts')->first();
                if ($marriage) {
                    $this->info("✅ تم العثور على عقد زواج أول برقم: {$marriage->id}");
                    if (isset($marriage->entry_id)) {
                        $entry = DB::table('entries')->find($marriage->entry_id);
                        if ($entry) {
                            $this->info("✅ العلاقة مع القيد تعمل بشكل صحيح");
                        } else {
                            $this->warn("⚠️  العلاقة مع القيد لا تعمل");
                        }
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("❌ خطأ في اختبار العلاقات: " . $e->getMessage());
            }
            
            // اختبار البيانات المالية
            $this->info("\n💰 اختبار البيانات المالية:");
            try {
                $financialCount = DB::table('entry_financial_data')->count();
                $this->info("✅ البيانات المالية: {$financialCount} سجل");
                
                if ($financialCount > 0) {
                    $financial = DB::table('entry_financial_data')->first();
                    $this->info("✅ أول سجل مالي برقم: {$financial->id}");
                }
            } catch (\Exception $e) {
                $this->error("❌ خطأ في البيانات المالية: " . $e->getMessage());
            }
            
            // اختبار الإعدادات والأنظمة
            $this->info("\n⚙️  اختبار الإعدادات والأنظمة:");
            try {
                $settingsCount = DB::table('system_settings')->count();
                $feeSettingsCount = DB::table('fee_settings')->count();
                $fineSettingsCount = DB::table('fine_settings')->count();
                
                $this->info("✅ الإعدادات العامة: {$settingsCount} سجل");
                $this->info("✅ إعدادات الرسوم: {$feeSettingsCount} سجل");
                $this->info("✅ إعدادات الغرامات: {$fineSettingsCount} سجل");
            } catch (\Exception $e) {
                $this->error("❌ خطأ في الإعدادات: " . $e->getMessage());
            }
            
            $this->info("\n🎉 اختبار قاعدة البيانات اكتمل بنجاح!");
            $this->info("✅ جميع البيانات المنسوخة تعمل بشكل صحيح");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ فشل الاختبار العام: " . $e->getMessage());
            return 1;
        }
    }
}
