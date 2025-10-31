<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SimpleCopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simple-copy-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نسخ بسيط لقاعدة البيانات باستخدام mysqldump';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء النسخ البسيط لقاعدة البيانات...');
        
        try {
            // إنشاء نسخة احتياطية باستخدام mysqldump
            $backupFile = storage_path('app/database_backup_' . date('Y-m-d_H-i-s') . '.sql');
            
            $command = sprintf(
                'mysqldump --host=127.0.0.1 --user=root --single-transaction --routines --triggers --default-character-set=utf8mb4 all_database_db > %s',
                $backupFile
            );
            
            $this->line('إنشاء نسخة احتياطية...');
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                $this->error('فشل في إنشاء النسخة الاحتياطية');
                return 1;
            }
            
            $this->info('تم إنشاء النسخة الاحتياطية: ' . $backupFile);
            
            // استعادة في قاعدة البيانات الجديدة
            $restoreCommand = sprintf(
                'mysql --host=127.0.0.1 --user=root --default-character-set=utf8mb4 filament_4x_db < %s',
                $backupFile
            );
            
            $this->line('استعادة قاعدة البيانات في filament_4x_db...');
            exec($restoreCommand, $output, $returnCode);
            
            if ($returnCode !== 0) {
                $this->error('فشل في استعادة قاعدة البيانات');
                return 1;
            }
            
            $this->info('✅ تم نسخ قاعدة البيانات بنجاح');
            
            // حذف الملف المؤقت
            File::delete($backupFile);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('خطأ: ' . $e->getMessage());
            return 1;
        }
    }
}
