<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RobustCopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:robust-copy-database {--force} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نسخ قاعدة البيانات all_database_db إلى filament_4x_db باستخدام mysqldump للتعامل مع الهياكل المعقدة';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 بدء عملية نسخ قاعدة البيانات المتقدمة...');
        
        // التحقق من وجود قاعدة البيانات المصدر
        if (!$this->checkSourceDatabase()) {
            return 1;
        }

        // إنشاء قاعدة البيانات الهدف
        if (!$this->createTargetDatabase()) {
            return 1;
        }

        // نسخ قاعدة البيانات باستخدام mysqldump
        if (!$this->copyWithMysqldump()) {
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
            
            // التحقق من الجداول في قاعدة البيانات المصدر
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
            // الاتصال بدون تحديد قاعدة بيانات
            $pdo = DB::connection()->getPdo();
            
            // التحقق من وجود قاعدة البيانات الهدف
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

            // إنشاء قاعدة البيانات الجديدة
            $pdo->exec("CREATE DATABASE filament_4x_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info('✅ تم إنشاء قاعدة البيانات filament_4x_db');
            
            return true;
        } catch (\Exception $e) {
            $this->error('❌ فشل في إنشاء قاعدة البيانات: ' . $e->getMessage());
            return false;
        }
    }

    private function copyWithMysqldump()
    {
        $this->info('📦 بدء نسخ قاعدة البيانات باستخدام mysqldump...');
        
        try {
            // إنشاء ملف مؤقت للنسخ الاحتياطي
            $tempFile = storage_path('app/temp_backup.sql');
            
            // التأكد من وجود مجلد التخزين المؤقت
            if (!File::exists(dirname($tempFile))) {
                File::makeDirectory(dirname($tempFile), 0755, true);
            }

            // أمر mysqldump لنسخ قاعدة البيانات
            $dumpCommand = [
                'mysqldump',
                '--host=127.0.0.1',
                '--user=root',
                '--single-transaction',
                '--routines',
                '--triggers',
                '--default-character-set=utf8mb4',
                'all_database_db'
            ];

            $this->line('🔄 إنشاء نسخة احتياطية...');
            $dumpProcess = new Process($dumpCommand);
            $dumpProcess->run();

            if (!$dumpProcess->isSuccessful()) {
                throw new ProcessFailedException($dumpProcess);
            }

            // حفظ النسخة الاحتياطية في ملف
            File::put($tempFile, $dumpProcess->getOutput());

            // أمر mysql لاستعادة قاعدة البيانات
            $restoreCommand = [
                'mysql',
                '--host=127.0.0.1',
                '--user=root',
                '--default-character-set=utf8mb4',
                'filament_4x_db'
            ];

            $this->line('🔄 استعادة قاعدة البيانات في filament_4x_db...');
            $restoreProcess = new Process($restoreCommand);
            $restoreProcess->setInput(File::get($tempFile));
            $restoreProcess->run();

            if (!$restoreProcess->isSuccessful()) {
                throw new ProcessFailedException($restoreProcess);
            }

            // حذف الملف المؤقت
            File::delete($tempFile);

            $this->info('✅ تم نسخ قاعدة البيانات بنجاح باستخدام mysqldump');
            return true;

        } catch (ProcessFailedException $e) {
            $this->error('❌ فشل في عملية mysqldump: ' . $e->getMessage());
            
            // محاولة الحل البديل
            $this->warn('🔄 محاولة الحل البديل...');
            return $this->fallbackCopy();
        } catch (\Exception $e) {
            $this->error('❌ خطأ غير متوقع: ' . $e->getMessage());
            return false;
        }
    }

    private function fallbackCopy()
    {
        try {
            $this->info('🔄 استخدام طريقة النسخ البديلة...');
            
            // العودة إلى قاعدة البيانات المصدر
            DB::statement('USE all_database_db');
            
            // الحصول على قائمة الجداول
            $tables = DB::select('SHOW TABLES');
            $tableNames = [];
            foreach ($tables as $table) {
                $tableNames[] = array_values((array)$table)[0];
            }

            $this->info('📊 نسخ ' . count($tableNames) . ' جدول...');

            foreach ($tableNames as $table) {
                $this->line("🔄 نسخ جدول: {$table}");
                
                // نسخ هيكل الجدول
                $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                $sql = $createTable[0]->{'Create Table'};
                
                // استبدال اسم قاعدة البيانات
                $sql = str_replace('all_database_db', 'filament_4x_db', $sql);
                
                // تنفيذ في قاعدة البيانات الجديدة
                $pdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
                $pdo->exec($sql);
                
                // نسخ البيانات
                $count = DB::table($table)->count();
                if ($count > 0) {
                    $pdo->exec("INSERT INTO filament_4x_db.`{$table}` SELECT * FROM all_database_db.`{$table}`");
                    $this->info("✅ {$table}: {$count} سجل");
                }
            }

            $this->info('✅ تم النسخ بنجاح باستخدام الطريقة البديلة');
            return true;

        } catch (\Exception $e) {
            $this->error('❌ فشلت الطريقة البديلة أيضاً: ' . $e->getMessage());
            return false;
        }
    }

    private function verifyCopy()
    {
        $this->info('\n🔍 التحقق من صحة النسخ...');
        
        try {
            // التحقق من قاعدة البيانات المصدر
            DB::statement('USE all_database_db');
            $sourceTables = DB::select('SHOW TABLES');
            $sourceCount = count($sourceTables);
            
            // التحقق من قاعدة البيانات الهدف
            $targetPdo = new \PDO("mysql:host=127.0.0.1;dbname=filament_4x_db", 'root', '');
            $targetTables = $targetPdo->query('SHOW TABLES')->fetchAll();
            $targetCount = count($targetTables);
            
            $this->info("📊 الجداول في المصدر: {$sourceCount}");
            $this->info("📊 الجداول في الهدف: {$targetCount}");
            
            if ($sourceCount !== $targetCount) {
                $this->warn('⚠️  عدد الجداول غير متطابق!');
                return false;
            }
            
            // التحقق من عدد السجلات
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
            
            // تحديث اسم قاعدة البيانات
            $envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=filament_4x_db', $envContent);
            
            File::put($envPath, $envContent);
            
            $this->info('✅ تم تحديث ملف .env للاتصال بقاعدة البيانات الجديدة');
            
            // إعادة تحميل إعدادات Laravel
            app()->terminating(function () {
                \Illuminate\Support\Facades\Artisan::call('config:cache');
            });
            
        } catch (\Exception $e) {
            $this->error('❌ فشل تحديث ملف .env: ' . $e->getMessage());
        }
    }
}
