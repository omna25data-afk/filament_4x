<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AnalyzeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analyze-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تحليل شامل لقاعدة البيانات all_database_db مع التركيز على الشروحات التوضيحية';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء تحليل قاعدة البيانات all_database_db...');
        
        try {
            // التحقق من الاتصال
            DB::select('SELECT 1');
            $this->info('✓ تم الاتصال بقاعدة البيانات بنجاح');
        } catch (\Exception $e) {
            $this->error('✗ فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
            return 1;
        }

        // الحصول على قائمة الجداول
        $tables = $this->getTables();
        $this->info('✓ تم العثور على ' . count($tables) . ' جدول');

        // تحليل كل جدول
        $analysis = [];
        foreach ($tables as $table) {
            $this->line("تحليل الجدول: {$table}");
            $analysis[$table] = $this->analyzeTable($table);
        }

        // تحليل العلاقات
        $this->info('\nتحليل العلاقات بين الجداول...');
        $relationships = $this->analyzeRelationships($tables);

        // إنشاء التقرير
        $this->generateReport($analysis, $relationships);

        $this->info('\n✓ اكتمل تحليل قاعدة البيانات بنجاح');
        return 0;
    }

    private function getTables()
    {
        $result = DB::select('SHOW TABLES');
        $tables = [];
        foreach ($result as $row) {
            $tables[] = array_values((array)$row)[0];
        }
        return $tables;
    }

    private function analyzeTable($table)
    {
        // الحصول على هيكل الجدول مع الشروحات
        $structure = DB::select("SHOW FULL COLUMNS FROM `{$table}`");
        
        // الحصول على شرح الجدول
        $tableComment = $this->getTableComment($table);
        
        // الحصول على عدد السجلات
        $count = DB::table($table)->count();

        $tableInfo = [
            'name' => $table,
            'comment' => $tableComment,
            'record_count' => $count,
            'columns' => [],
            'indexes' => $this->getTableIndexes($table),
            'foreign_keys' => $this->getForeignKeys($table)
        ];

        foreach ($structure as $column) {
            $tableInfo['columns'][] = [
                'name' => $column->Field,
                'type' => $column->Type,
                'null' => $column->Null,
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra,
                'comment' => $column->Comment
            ];
        }

        return $tableInfo;
    }

    private function getTableComment($table)
    {
        try {
            $result = DB::select("
                SELECT TABLE_COMMENT 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ?
            ", [$table]);
            
            return $result[0]->TABLE_COMMENT ?? '';
        } catch (\Exception $e) {
            return '';
        }
    }

    private function getTableIndexes($table)
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM `{$table}`");
            $indexList = [];
            foreach ($indexes as $index) {
                $indexList[] = [
                    'name' => $index->Key_name,
                    'column' => $index->Column_name,
                    'unique' => $index->Non_unique == 0,
                    'type' => $index->Index_type
                ];
            }
            return $indexList;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getForeignKeys($table)
    {
        try {
            $fks = DB::select("
                SELECT 
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME,
                    CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$table]);
            
            $fkList = [];
            foreach ($fks as $fk) {
                $fkList[] = [
                    'column' => $fk->COLUMN_NAME,
                    'references_table' => $fk->REFERENCED_TABLE_NAME,
                    'references_column' => $fk->REFERENCED_COLUMN_NAME,
                    'constraint_name' => $fk->CONSTRAINT_NAME
                ];
            }
            return $fkList;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function analyzeRelationships($tables)
    {
        $relationships = [];
        foreach ($tables as $table) {
            $fks = $this->getForeignKeys($table);
            foreach ($fks as $fk) {
                $relationships[] = [
                    'from_table' => $table,
                    'from_column' => $fk['column'],
                    'to_table' => $fk['references_table'],
                    'to_column' => $fk['references_column'],
                    'constraint_name' => $fk['constraint_name']
                ];
            }
        }
        return $relationships;
    }

    private function generateReport($analysis, $relationships)
    {
        $report = "# تقرير تحليل قاعدة البيانات all_database_db\n\n";
        $report .= "تاريخ التحليل: " . date('Y-m-d H:i:s') . "\n\n";
        
        // ملخص عام
        $report .= "## ملخص عام\n\n";
        $report .= "- عدد الجداول: " . count($analysis) . "\n";
        $report .= "- عدد العلاقات: " . count($relationships) . "\n";
        
        $totalRecords = array_sum(array_column($analysis, 'record_count'));
        $report .= "- إجمالي السجلات: " . number_format($totalRecords) . "\n\n";

        // تحليل الجداول
        $report .= "## تحليل الجداول\n\n";
        foreach ($analysis as $table) {
            $report .= "### جدول: `{$table['name']}`\n\n";
            
            if ($table['comment']) {
                $report .= "**الوصف:** " . $table['comment'] . "\n\n";
            } else {
                $report .= "**الوصف:** *لا يوجد وصف توضيحي*\n\n";
            }
            
            $report .= "**عدد السجلات:** " . number_format($table['record_count']) . "\n\n";
            
            $report .= "**الحقول:**\n\n";
            $report .= "| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |\n";
            $report .= "|-----------|-------|-------|---------|-----------|-------|-------|\n";
            
            foreach ($table['columns'] as $column) {
                $report .= "| `{$column['name']}` | {$column['type']} | {$column['null']} | {$column['key']} | " . 
                           ($column['default'] ?? 'NULL') . " | {$column['extra']} | " . 
                           ($column['comment'] ?: '*لا يوجد وصف*') . " |\n";
            }
            
            if (!empty($table['foreign_keys'])) {
                $report .= "\n**المفاتيح الخارجية:**\n\n";
                foreach ($table['foreign_keys'] as $fk) {
                    $report .= "- `{$fk['column']}` → `{$fk['references_table']}`.`{$fk['references_column']}`\n";
                }
            }
            
            $report .= "\n---\n\n";
        }

        // العلاقات بين الجداول
        $report .= "## العلاقات بين الجداول\n\n";
        if (!empty($relationships)) {
            foreach ($relationships as $rel) {
                $report .= "- `{$rel['from_table']}`.`{$rel['from_column']}` → `{$rel['to_table']}`.`{$rel['to_column']}`\n";
            }
        } else {
            $report .= "*لا توجد علاقات محددة*\n";
        }

        // حفظ التقرير
        File::put(database_path('analysis_report.md'), $report);
        $this->info('✓ تم حفظ التقرير في: database/analysis_report.md');
        
        // عرض ملخص في الطرفية
        $this->info('\n=== ملخص التحليل ===');
        $this->info('عدد الجداول: ' . count($analysis));
        $this->info('إجمالي السجلات: ' . number_format($totalRecords));
        $this->info('عدد العلاقات: ' . count($relationships));
        
        // جداول بدون وصف
        $tablesWithoutComment = array_filter($analysis, fn($t) => empty($t['comment']));
        if (!empty($tablesWithoutComment)) {
            $this->warn('\n⚠ جداول بدون وصف توضيحي:');
            foreach ($tablesWithoutComment as $table) {
                $this->warn('- ' . $table['name']);
            }
        }
    }
}
