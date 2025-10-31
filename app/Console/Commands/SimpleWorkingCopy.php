<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SimpleWorkingCopy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simple-working-copy {--force} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نسخ قاعدة البيانات بطريقة بسيطة وفعالة';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء عملية نسخ قاعدة البيانات البسيطة والفعالة...');
        
        // التحقق من وجود قاعدة البيانات المصدر
        if (!$this->checkSourceDatabase()) {
            return 1;
        }

        // إنشاء قاعدة البيانات الهدف
        if (!$this->createTargetDatabase()) {
            return 1;
        }

        // نسخ الجداول باستخدام طريقة مباشرة
        if (!$this->copyTablesDirectly()) {
            return 1;
        }

        // التحقق من صحة النسخ (اختياري)
        if ($this->option('verify')) {
            $this->verifyCopy();
        }

        // تحديث الاتصال لقاعدة البيانات الجديدة
        $this->updateConnection();

        $this->info('✅ تم نسخ قاعدة البيانات بنجاح إلى filament_4x_db');
        return 0;
    }

    private function checkSourceDatabase()
    {
        try {
            $result = DB::select("SHOW DATABASES LIKE 'all_database_db'");
            if (empty($result)) {
                $this->error('❌ قاعدة البيانات all_database_db غير موجودة');
                return false;
            }
            
            DB::statement('USE all_database_db');
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            
            $this->info("📊 قاعدة البيانات المصدر تحتوي على {$tableCount} جدول");
            return true;
        } catch (\Exception $e) {
            $this->error('❌ فشل في الوصول لقاعدة البيانات المصدر: ' . $e->getMessage());
            return false;
        }
    }

    private function createTargetDatabase()
    {
        try {
            $pdo = DB::connection()->getPdo();
            
            $stmt = $pdo->prepare("SHOW DATABASES LIKE 'filament_4x_db'");
            $stmt->execute();
            $exists = $stmt->fetch() !== false;

            if ($exists && !$this->option('force')) {
                if (!$this->confirm('⚠️  قاعدة البيانات filament_4x_db موجودة بالفعل. هل تريد حذفها وإنشائها من جديد؟')) {
                    $this->error('❌ تم إلغاء العملية.');
                    return false;
                }
            }

            if ($exists) {
                $pdo->exec("DROP DATABASE filament_4x_db");
                $this->info('🗑️  تم حذف قاعدة البيانات القديمة.');
            }

            $pdo->exec("CREATE DATABASE filament_4x_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info('✅ تم إنشاء قاعدة البيانات filament_4x_db');
            
            return true;
        } catch (\Exception $e) {
            $this->error('❌ فشل في إنشاء قاعدة البيانات: ' . $e->getMessage());
            return false;
        }
    }

    private function copyTablesDirectly()
    {
        $this->info('📋 نسخ الجداول مباشرة...');
        
        try {
            DB::statement('USE all_database_db');
            $tables = DB::select('SHOW TABLES');
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            $totalRecords = 0;
            
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                $this->line("🔄 نسخ جدول: {$tableName}");
                
                // نسخ الجدول بالكامل باستخدام CREATE TABLE ... SELECT
                try {
                    // أولاً نسخ الهيكل فقط
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    $sql = $createTable[0]->{'Create Table'};
                    
                    // استبدال اسم قاعدة البيانات
                    $sql = preg_replace('/CREATE TABLE\s+`?all_database_db`?/', 'CREATE TABLE', $sql);
                    
                    // إزالة اسم قاعدة البيانات من نهاية الاستعلام
                    $sql = preg_replace('/\s*ENGINE=\w+.*$/', '', $sql);
                    
                    // تنفيذ إنشاء الجدول
                    $targetPdo->exec($sql);
                    
                    // نسخ البيانات
                    $count = DB::table($tableName)->count();
                    if ($count > 0) {
                        $targetPdo->exec("INSERT INTO `{$tableName}` SELECT * FROM all_database_db.`{$tableName}`");
                        $totalRecords += $count;
                        $this->info("✅ {$tableName}: {$count} سجل");
                    } else {
                        $this->info("✅ {$tableName}: جدول فارغ");
                    }
                    
                } catch (\Exception $e) {
                    $this->warn("⚠️  فشل نسخ الجدول {$tableName}: " . $e->getMessage());
                    
                    // محاولة الطريقة البديلة
                    try {
                        $this->line("🔄 محاولة الطريقة البديلة لـ {$tableName}");
                        
                        // إنشاء جدول فارغ بنفس الهيكل
                        $targetPdo->exec("CREATE TABLE `{$tableName}` LIKE all_database_db.`{$tableName}`");
                        
                        // نسخ البيانات
                        $count = DB::table($tableName)->count();
                        if ($count > 0) {
                            $targetPdo->exec("INSERT INTO `{$tableName}` SELECT * FROM all_database_db.`{$tableName}`");
                            $totalRecords += $count;
                            $this->info("✅ {$tableName}: {$count} سجل (طريقة بديلة)");
                        }
                        
                    } catch (\Exception $e2) {
                        $this->error("❌ فشلت الطريقة البديلة أيضاً لـ {$tableName}: " . $e2->getMessage());
                    }
                }
            }
            
            $this->info("📊 إجمالي السجلات المنسوخة: {$totalRecords}");
            return true;
            
        } catch (\Exception $e) {
            $this->error('❌ فشل نسخ الجداول: ' . $e->getMessage());
            return false;
        }
    }

    private function verifyCopy()
    {
        $this->info('\n🔍 التحقق من صحة النسخ...');
        
        try {
            DB::statement('USE all_database_db');
            $sourceTables = DB::select('SHOW TABLES');
            $sourceCount = count($sourceTables);
            
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            $targetTables = $targetPdo->query('SHOW TABLES')->fetchAll();
            $targetCount = count($targetTables);
            
            $this->info("📊 الجداول في المصدر: {$sourceCount}");
            $this->info("📊 الجداول في الهدف: {$targetCount}");
            
            $totalSourceRecords = 0;
            $totalTargetRecords = 0;
            $mismatchedTables = [];
            
            foreach ($sourceTables as $table) {
                $tableName = array_values((array)$table)[0];
                
                $sourceRecords = DB::table($tableName)->count();
                
                try {
                    $targetRecords = $targetPdo->query("SELECT COUNT(*) FROM `{$tableName}`")->fetchColumn();
                } catch (\Exception $e) {
                    $targetRecords = 0;
                    $mismatchedTables[] = $tableName . " (غير موجود)";
                }
                
                $totalSourceRecords += $sourceRecords;
                $totalTargetRecords += $targetRecords;
                
                if ($sourceRecords !== $targetRecords) {
                    $mismatchedTables[] = $tableName . ": {$sourceRecords} ↔ {$targetRecords}";
                }
            }
            
            $this->info("📊 إجمالي السجلات المصدر: {$totalSourceRecords}");
            $this->info("📊 إجمالي السجلات الهدف: {$totalTargetRecords}");
            
            if (!empty($mismatchedTables)) {
                $this->warn('⚠️  جداول بها اختلافات:');
                foreach ($mismatchedTables as $table) {
                    $this->line("   - {$table}");
                }
            }
            
            if ($totalSourceRecords === $totalTargetRecords && empty($mismatchedTables)) {
                $this->info('✅ التحقق من صحة النسخ نجح!');
                return true;
            } else {
                $this->warn('⚠️  هناك اختلافات في النسخ!');
                return false;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ فشل التحقق: ' . $e->getMessage());
            return false;
        }
    }

    private function updateConnection()
    {
        try {
            $envPath = base_path('.env');
            $envContent = File::get($envPath);
            
            $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=filament_4x_db', $envContent);
            
            File::put($envPath, $envContent);
            
            $this->info('✅ تم تحديث ملف .env للاتصال بقاعدة البيانات الجديدة');
            
        } catch (\Exception $e) {
            $this->error('❌ فشل تحديث ملف .env: ' . $e->getMessage());
        }
    }
}
