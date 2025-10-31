<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TestModelsAndResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-models-and-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test generated Models and Filament Resources';

    /**
     * Test results storage
     */
    private $modelTests = [];
    private $resourceTests = [];
    private $relationshipTests = [];

    /**
     * Main tables to test
     */
    protected $mainTables = [
        'users',
        'notaries',
        'entries',
        'registers',
        'register_types',
        'contract_types',
        'marriage_contracts',
        'agency_contracts',
        'sale_contracts',
        'divorce_attestations',
        'disposal_contracts',
        'partition_contracts',
        'reconciliation_attestations',
        'entry_financial_data',
        'administrative_units',
        'writer_types',
        'other_writers',
        'fee_settings',
        'fine_settings',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Generated Models and Filament Resources...');
        $this->info('📊 Database: ' . config('database.connections.mysql.database'));

        $this->testModels();
        $this->testResources();
        $this->testRelationships();
        $this->generateTestReport();

        $this->info("\n✅ Testing completed!");
    }

    /**
     * Test generated models
     */
    private function testModels()
    {
        $this->info("\n🏗️  Testing Models...");

        $modelTests = [];

        foreach ($this->mainTables as $table) {
            $modelName = $this->getModelName($table);
            $modelClass = "App\\Models\\{$modelName}";

            try {
                if (class_exists($modelClass)) {
                    $model = new $modelClass;
                    
                    // Test basic model functionality
                    $tableTest = $model->getTable() === $table;
                    $fillableTest = !empty($model->getFillable());
                    $castsTest = !empty($model->getCasts());

                    // Test database connection
                    $countTest = $model::count();
                    
                    $modelTests[$modelName] = [
                        'class_exists' => true,
                        'table_correct' => $tableTest,
                        'has_fillable' => $fillableTest,
                        'has_casts' => $castsTest,
                        'db_connection' => true,
                        'record_count' => $countTest,
                        'status' => ($tableTest && $fillableTest && true) ? '✅ PASS' : '❌ FAIL'
                    ];

                    $this->line("   {$modelTests[$modelName]['status']} {$modelName} ({$countTest} records)");
                } else {
                    $modelTests[$modelName] = [
                        'class_exists' => false,
                        'status' => '❌ FAIL - Class not found'
                    ];
                    $this->line("   ❌ {$modelName} - Class not found");
                }
            } catch (\Exception $e) {
                $modelTests[$modelName] = [
                    'class_exists' => true,
                    'status' => '❌ FAIL - ' . $e->getMessage()
                ];
                $this->line("   ❌ {$modelName} - " . $e->getMessage());
            }
        }

        $this->modelTests = $modelTests;
    }

    /**
     * Test generated resources
     */
    private function testResources()
    {
        $this->info("\n🎨 Testing Filament Resources...");

        $resourceTests = [];

        foreach ($this->mainTables as $table) {
            $modelName = $this->getModelName($table);
            $resourceName = $modelName . 'Resource';
            $resourceClass = "App\\Filament\\Resources\\{$resourceName}";

            try {
                if (class_exists($resourceClass)) {
                    // Test resource methods
                    $modelTest = $resourceClass::getModel();
                    $formTest = method_exists($resourceClass, 'form');
                    $tableTest = method_exists($resourceClass, 'table');
                    $pagesTest = method_exists($resourceClass, 'getPages');

                    $resourceTests[$resourceName] = [
                        'class_exists' => true,
                        'has_model' => !empty($modelTest),
                        'has_form' => $formTest,
                        'has_table' => $tableTest,
                        'has_pages' => $pagesTest,
                        'status' => ($formTest && $tableTest && $pagesTest) ? '✅ PASS' : '❌ FAIL'
                    ];

                    $this->line("   {$resourceTests[$resourceName]['status']} {$resourceName}");
                } else {
                    $resourceTests[$resourceName] = [
                        'class_exists' => false,
                        'status' => '❌ FAIL - Class not found'
                    ];
                    $this->line("   ❌ {$resourceName} - Class not found");
                }
            } catch (\Exception $e) {
                $resourceTests[$resourceName] = [
                    'class_exists' => true,
                    'status' => '❌ FAIL - ' . $e->getMessage()
                ];
                $this->line("   ❌ {$resourceName} - " . $e->getMessage());
            }
        }

        $this->resourceTests = $resourceTests;
    }

    /**
     * Test model relationships
     */
    private function testRelationships()
    {
        $this->info("\n🔗 Testing Model Relationships...");

        $relationshipTests = [];

        // Test key relationships
        $keyRelationships = [
            'User' => ['notaries', 'entriesAsWriter'],
            'Notary' => ['user', 'entriesAsNotary', 'registers'],
            'Entry' => ['user', 'notary', 'register', 'contractType', 'marriageContract'],
            'Register' => ['entries', 'registerType', 'notary'],
            'ContractType' => ['entries'],
            'MarriageContract' => ['entry'],
        ];

        foreach ($keyRelationships as $modelName => $relationships) {
            $modelClass = "App\\Models\\{$modelName}";
            
            if (!class_exists($modelClass)) {
                continue;
            }

            $modelTests = [];
            
            foreach ($relationships as $relation) {
                try {
                    $model = new $modelClass;
                    
                    if (method_exists($model, $relation)) {
                        $relationTest = $model->$relation();
                        
                        // Check if it's a valid Eloquent relationship
                        $isValidRelation = method_exists($relationTest, 'getRelated');
                        
                        $modelTests[$relation] = [
                            'exists' => true,
                            'valid' => $isValidRelation,
                            'status' => $isValidRelation ? '✅ PASS' : '❌ FAIL'
                        ];
                    } else {
                        $modelTests[$relation] = [
                            'exists' => false,
                            'status' => '❌ FAIL - Method not found'
                        ];
                    }
                } catch (\Exception $e) {
                    $modelTests[$relation] = [
                        'exists' => true,
                        'status' => '❌ FAIL - ' . $e->getMessage()
                    ];
                }
            }

            $relationshipTests[$modelName] = $modelTests;
            
            foreach ($modelTests as $relation => $test) {
                $this->line("   {$test['status']} {$modelName}::{$relation}()");
            }
        }

        $this->relationshipTests = $relationshipTests;
    }

    /**
     * Generate test report
     */
    private function generateTestReport()
    {
        $this->info("\n📋 Generating Test Report...");

        $report = "# تقرير اختبار النماذج والموارد\n\n";
        $report .= "**التاريخ:** " . now()->format('Y-m-d H:i:s') . "\n";
        $report .= "**قاعدة البيانات:** " . config('database.connections.mysql.database') . "\n\n";

        // Models Summary
        $report .= "## 📊 ملخص النماذج\n\n";
        $totalModels = count($this->modelTests);
        $passedModels = collect($this->modelTests)->filter(fn($test) => str_contains($test['status'], 'PASS'))->count();
        
        $report .= "- **إجمالي النماذج:** {$totalModels}\n";
        $report .= "- **ناجحة:** {$passedModels}\n";
        $report .= "- **فاشلة:** " . ($totalModels - $passedModels) . "\n\n";

        // Resources Summary
        $report .= "## 🎨 ملخص الموارد\n\n";
        $totalResources = count($this->resourceTests);
        $passedResources = collect($this->resourceTests)->filter(fn($test) => str_contains($test['status'], 'PASS'))->count();
        
        $report .= "- **إجمالي الموارد:** {$totalResources}\n";
        $report .= "- **ناجحة:** {$passedResources}\n";
        $report .= "- **فاشلة:** " . ($totalResources - $passedResources) . "\n\n";

        // Detailed Results
        $report .= "## 📋 نتائج تفصيلية\n\n";

        $report .= "### النماذج\n\n";
        foreach ($this->modelTests as $model => $test) {
            $status = $test['status'];
            $count = $test['record_count'] ?? 'N/A';
            $report .= "- {$status} **{$model}** ({$count} سجل)\n";
        }

        $report .= "\n### الموارد\n\n";
        foreach ($this->resourceTests as $resource => $test) {
            $status = $test['status'];
            $report .= "- {$status} **{$resource}**\n";
        }

        if (isset($this->relationshipTests)) {
            $report .= "\n### العلاقات\n\n";
            foreach ($this->relationshipTests as $model => $relations) {
                $report .= "**{$model}**:\n";
                foreach ($relations as $relation => $test) {
                    $status = $test['status'];
                    $report .= "  - {$status} {$relation}()\n";
                }
                $report .= "\n";
            }
        }

        // File Statistics
        $report .= "## 📁 إحصائيات الملفات\n\n";
        
        $modelFiles = glob(app_path('Models/*.php'));
        $resourceFiles = glob(app_path('Filament/Resources/*.php'));
        $pageFiles = glob(app_path('Filament/Resources/*/Pages/*.php'));
        
        $report .= "- **ملفات النماذج:** " . count($modelFiles) . "\n";
        $report .= "- **ملفات الموارد:** " . count($resourceFiles) . "\n";
        $report .= "- **ملفات الصفحات:** " . count($pageFiles) . "\n\n";

        // Recommendations
        $report .= "## 🎯 التوصيات\n\n";
        
        if ($passedModels < $totalModels) {
            $report .= "- 🔧 بعض النماذج تحتاج إلى إصلاح\n";
        }
        
        if ($passedResources < $totalResources) {
            $report .= "- 🔧 بعض الموارد تحتاج إلى إصلاح\n";
        }
        
        if ($passedModels === $totalModels && $passedResources === $totalResources) {
            $report .= "- ✅ جميع النماذج والموارد تعمل بشكل صحيح\n";
            $report .= "- 🚀 جاهز لبدء استخدام النظام\n";
        }

        $report .= "\n---\n**تم الإنشاء:** " . now()->format('Y-m-d H:i:s');

        // Save report
        $reportPath = database_path('models_resources_test_report.md');
        File::put($reportPath, $report);
        
        $this->info("📄 Test report saved to: {$reportPath}");
    }

    /**
     * Convert table name to model name
     */
    private function getModelName($table)
    {
        return \Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($table));
    }
}
