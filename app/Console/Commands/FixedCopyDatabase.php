<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FixedCopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fixed-copy-database {--force} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نسخ قاعدة البيانات مع معالجة المفاتيح الخارجية بشكل صحيح';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء عملية نسخ قاعدة البيانات المحسّنة...');
        
        // التحقق من وجود قاعدة البيانات المصدر
        if (!$this->checkSourceDatabase()) {
            return 1;
        }

        // إنشاء قاعدة البيانات الهدف
        if (!$this->createTargetDatabase()) {
            return 1;
        }

        // نسخ الهياكل بدون مفاتيح خارجية
        if (!$this->copyTableStructures()) {
            return 1;
        }

        // نسخ البيانات
        if (!$this->copyTableData()) {
            return 1;
        }

        // إعادة إنشاء المفاتيح الخارجية
        if (!$this->recreateForeignKeys()) {
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

    private function copyTableStructures()
    {
        $this->info('📋 نسخ هياكل الجداول...');
        
        try {
            DB::statement('USE all_database_db');
            $tables = DB::select('SHOW TABLES');
            
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                $this->line("🔄 نسخ هيكل جدول: {$tableName}");
                
                // الحصول على هيكل الجدول
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql = $createTable[0]->{'Create Table'};
                
                // إزالة المفاتيح الخارجية والقيود
                $sql = $this->removeConstraints($sql);
                
                // استبدال اسم قاعدة البيانات
                $sql = str_replace('all_database_db', 'filament_4x_db', $sql);
                
                $targetPdo->exec($sql);
                $this->info("✅ تم نسخ هيكل: {$tableName}");
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->error('❌ فشل نسخ الهياكل: ' . $e->getMessage());
            return false;
        }
    }

    private function removeConstraints($sql)
    {
        // إزالة قيود المفاتيح الخارجية بشكل أكثر دقة
        $lines = explode("\n", $sql);
        $result = [];
        $parenthesesCount = 0;
        $inForeignKey = false;
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // التحقق من بداية قيد المفتاح الخارجي
            if (preg_match('/CONSTRAINT\s+\w+\s+FOREIGN\s+KEY/i', $trimmed)) {
                $inForeignKey = true;
                $parenthesesCount = substr_count($line, '(') - substr_count($line, ')');
                continue;
            }
            
            // إذا كنا داخل قيد المفتاح الخارجي
            if ($inForeignKey) {
                $parenthesesCount += substr_count($line, '(') - substr_count($line, ')');
                
                // نهاية القيد عندما نصل إلى قوس مغلق
                if ($parenthesesCount <= 0 && strpos($line, ')') !== false) {
                    $inForeignKey = false;
                    $parenthesesCount = 0;
                }
                continue;
            }
            
            // إزالة أسطر CONSTRAINT وحدها
            if (preg_match('/^\s*CONSTRAINT\s+/i', $trimmed)) {
                continue;
            }
            
            $result[] = $line;
        }
        
        // تنظيف الأسطر الفارغة الزائدة
        $result = array_filter($result, function($line) {
            return trim($line) !== '';
        });
        
        return implode("\n", $result);
    }

    private function copyTableData()
    {
        $this->info('📊 نسخ بيانات الجداول...');
        
        try {
            DB::statement('USE all_database_db');
            $tables = DB::select('SHOW TABLES');
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            $totalRecords = 0;
            
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                
                $count = DB::table($tableName)->count();
                if ($count > 0) {
                    $this->line("🔄 نسخ {$count} سجل من جدول {$tableName}");
                    
                    // نسخ البيانات باستخدام INSERT INTO ... SELECT
                    $targetPdo->exec("INSERT INTO filament_4x_db.`{$tableName}` SELECT * FROM all_database_db.`{$tableName}`");
                    
                    $totalRecords += $count;
                    $this->info("✅ تم نسخ {$count} سجل من {$tableName}");
                }
            }
            
            $this->info("📊 إجمالي السجلات المنسوخة: {$totalRecords}");
            return true;
            
        } catch (\Exception $e) {
            $this->error('❌ فشل نسخ البيانات: ' . $e->getMessage());
            return false;
        }
    }

    private function recreateForeignKeys()
    {
        $this->info('🔗 إعادة إنشاء المفاتيح الخارجية...');
        
        try {
            DB::statement('USE all_database_db');
            $tables = DB::select('SHOW TABLES');
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                
                // الحصول على قيود المفاتيح الخارجية
                $constraints = DB::select("
                    SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = 'all_database_db' 
                    AND TABLE_NAME = '{$tableName}' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($constraints)) {
                    $this->line("🔄 إنشاء مفاتيح خارجية لجدول: {$tableName}");
                    
                    foreach ($constraints as $constraint) {
                        $constraintName = $constraint->CONSTRAINT_NAME;
                        $columnName = $constraint->COLUMN_NAME;
                        $referencedTable = $constraint->REFERENCED_TABLE_NAME;
                        $referencedColumn = $constraint->REFERENCED_COLUMN_NAME;
                        
                        $alterSql = "ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$constraintName}` 
                                    FOREIGN KEY (`{$columnName}`) REFERENCES `{$referencedTable}`(`{$referencedColumn}`)";
                        
                        try {
                            $targetPdo->exec($alterSql);
                            $this->info("✅ تم إنشاء المفتاح الخارجي: {$constraintName}");
                        } catch (\Exception $e) {
                            $this->warn("⚠️  فشل إنشاء المفتاح الخارجي {$constraintName}: " . $e->getMessage());
                        }
                    }
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->error('❌ فشل إعادة إنشاء المفاتيح الخارجية: ' . $e->getMessage());
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
            
            foreach ($sourceTables as $table) {
                $tableName = array_values((array)$table)[0];
                
                $sourceRecords = DB::table($tableName)->count();
                $targetRecords = $targetPdo->query("SELECT COUNT(*) FROM `{$tableName}`")->fetchColumn();
                
                $totalSourceRecords += $sourceRecords;
                $totalTargetRecords += $targetRecords;
                
                if ($sourceRecords !== $targetRecords) {
                    $this->warn("⚠️  جدول {$tableName}: {$sourceRecords} ↔ {$targetRecords}");
                }
            }
            
            $this->info("📊 إجمالي السجلات المصدر: {$totalSourceRecords}");
            $this->info("📊 إجمالي السجلات الهدف: {$totalTargetRecords}");
            
            if ($totalSourceRecords === $totalTargetRecords) {
                $this->info('✅ التحقق من صحة النسخ نجح!');
                return true;
            } else {
                $this->warn('⚠️  هناك اختلاف في عدد السجلات!');
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
