<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:copy-database {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نسخ قاعدة البيانات all_database_db إلى filament_4x_db مع التحسينات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء عملية نسخ قاعدة البيانات...');
        
        // الحصول على قائمة الجداول أولاً
        $tables = $this->getTables();
        $this->info('تم العثور على ' . count($tables) . ' جدول');
        
        // التحقق من وجود قاعدة البيانات الهدف
        if (!$this->createTargetDatabase()) {
            return 1;
        }

        // نسخ الجداول
        foreach ($tables as $table) {
            $this->copyTable($table);
        }

        // نسخ البيانات
        $this->copyData();

        // تحديث الاتصال لقاعدة البيانات الجديدة
        $this->updateConnection();

        $this->info('✓ تم نسخ قاعدة البيانات بنجاح إلى filament_4x_db');
        return 0;
    }

    private function createTargetDatabase()
    {
        try {
            // الاتصال بدون تحديد قاعدة بيانات
            $pdo = DB::connection()->getPdo();
            
            // التحقق من وجود قاعدة البيانات
            $stmt = $pdo->prepare("SHOW DATABASES LIKE 'filament_4x_db'");
            $stmt->execute();
            $exists = $stmt->fetch() !== false;

            if ($exists && !$this->option('force')) {
                if (!$this->confirm('قاعدة البيانات filament_4x_db موجودة بالفعل. هل تريد حذفها وإنشائها من جديد؟')) {
                    $this->error('تم إلغاء العملية.');
                    return false;
                }
            }

            if ($exists) {
                $pdo->exec("DROP DATABASE filament_4x_db");
                $this->info('تم حذف قاعدة البيانات القديمة.');
            }

            // إنشاء قاعدة البيانات الجديدة
            $pdo->exec("CREATE DATABASE filament_4x_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info('✓ تم إنشاء قاعدة البيانات filament_4x_db');
            
            return true;
        } catch (\Exception $e) {
            $this->error('فشل في إنشاء قاعدة البيانات: ' . $e->getMessage());
            return false;
        }
    }

    private function getTables()
    {
        $result = DB::select('SHOW TABLES');
        $tables = [];
        foreach ($result as $row) {
            $tableName = array_values((array)$row)[0];
            // استبعاد الجداول النظامية التي لا نحتاجها
            if (!in_array($tableName, ['cache', 'cache_locks', 'failed_jobs', 'job_batches', 'jobs', 'migrations', 'password_reset_tokens'])) {
                $tables[] = $tableName;
            }
        }
        return $tables;
    }

    private function copyTable($table)
    {
        try {
            $this->line("نسخ هيكل الجدول: {$table}");
            
            // الحصول على هيكل الجدول
            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            $sql = $createTable[0]->{'Create Table'};
            
            // استبدال اسم قاعدة البيانات
            $sql = str_replace('all_database_db', 'filament_4x_db', $sql);
            
            // إزالة المفاتيح الخارجية مؤقتاً
            $sql = $this->removeForeignKeys($sql);
            
            // إضافة شرح توضيحي للجداول القديمة
            if (in_array($table, ['قيود_التصرفات', 'قيود_الرجعة', 'قيود_الزواج', 'قيود_الطلاق', 'قيود_القسمة', 'قيود_المبيع', 'قيود_الوكالات'])) {
                $comment = $this->getTableComment($table);
                if ($comment) {
                    $sql .= " COMMENT='{$comment}'";
                }
            }
            
            // الاتصال بقاعدة البيانات الجديدة وتنفيذ الاستعلام
            $pdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            $pdo->exec($sql);
            
            $this->info("✓ تم نسخ هيكل الجدول: {$table}");
        } catch (\Exception $e) {
            $this->error("✗ فشل نسخ الجدول {$table}: " . $e->getMessage());
        }
    }

    private function removeForeignKeys($sql)
    {
        // إزالة قيود المفاتيح الخارجية من CREATE TABLE
        $lines = explode("\n", $sql);
        $result = [];
        $inConstraint = false;
        $parenthesesCount = 0;
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // عد الأقواس للتأكد من نهاية CONSTRAINT
            $parenthesesCount += substr_count($line, '(') - substr_count($line, ')');
            
            // تخطي قيود المفاتيح الخارجية
            if (strpos($trimmed, 'CONSTRAINT') !== false || $inConstraint) {
                $inConstraint = true;
                if ($parenthesesCount <= 0 && strpos($trimmed, ')') !== false) {
                    $inConstraint = false;
                    $parenthesesCount = 0;
                }
                continue;
            }
            
            $result[] = $line;
        }
        
        return implode("\n", $result);
    }

    private function getTableComment($table)
    {
        $comments = [
            'قيود_التصرفات' => 'جدول قيود التصرفات القديمة - تم نقله إلى disposal_contracts',
            'قيود_الرجعة' => 'جدول قيود الرجعة القديمة - تم نقله إلى reconciliation_attestations',
            'قيود_الزواج' => 'جدول قيود الزواج القديمة - تم نقله إلى marriage_contracts',
            'قيود_الطلاق' => 'جدول قيود الطلاق القديمة - تم نقله إلى divorce_attestations',
            'قيود_القسمة' => 'جدول قيود القسمة القديمة - تم نقله إلى partition_contracts',
            'قيود_المبيع' => 'جدول قيود المبيع القديمة - تم نقله إلى sale_contracts',
            'قيود_الوكالات' => 'جدول قيود الوكالات القديمة - تم نقله إلى agency_contracts'
        ];
        
        return $comments[$table] ?? '';
    }

    private function copyData()
    {
        $this->info('\nبدء نسخ البيانات...');
        
        $tables = $this->getTables();
        $totalRecords = 0;
        
        foreach ($tables as $table) {
            try {
                $count = DB::table($table)->count();
                if ($count > 0) {
                    $this->line("نسخ {$count} سجل من جدول {$table}");
                    
                    // نسخ البيانات باستخدام INSERT INTO ... SELECT
                    $pdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
                    $pdo->exec("INSERT INTO filament_4x_db.`{$table}` SELECT * FROM all_database_db.`{$table}`");
                    
                    $totalRecords += $count;
                    $this->info("✓ تم نسخ {$count} سجل من {$table}");
                }
            } catch (\Exception $e) {
                $this->error("✗ فشل نسخ بيانات الجدول {$table}: " . $e->getMessage());
            }
        }
        
        $this->info("\n✓ تم نسخ إجمالي {$totalRecords} سجل");
    }

    private function updateConnection()
    {
        $envPath = base_path('.env');
        $envContent = File::get($envPath);
        
        // تحديث اسم قاعدة البيانات
        $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=filament_4x_db', $envContent);
        
        File::put($envPath, $envContent);
        
        $this->info('✓ تم تحديث ملف .env للاتصال بقاعدة البيانات الجديدة');
    }
}
