<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateModelsAndResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-models-and-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Eloquent Models and Filament Resources for all database tables';

    /**
     * Tables to skip (Laravel system tables)
     */
    protected $skipTables = [
        'migrations',
        'password_reset_tokens',
        'failed_jobs',
        'jobs',
        'job_batches',
        'cache',
        'cache_locks',
        'sessions',
    ];

    /**
     * Main tables that need Filament Resources
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
        $this->info('🚀 Starting generation of Models and Filament Resources...');
        $this->info('📊 Database: ' . config('database.connections.mysql.database'));

        // Get all tables
        $tables = $this->getAllTables();
        
        $this->info("\n📋 Found " . count($tables) . " tables");
        
        // Generate Models
        $this->generateModels($tables);
        
        // Generate Filament Resources for main tables
        $this->generateFilamentResources($this->mainTables);
        
        $this->info("\n✅ Generation completed successfully!");
        $this->info("📁 Models created in: app/Models/");
        $this->info("📁 Resources created in: app/Filament/Resources/");
    }

    /**
     * Get all tables from the database
     */
    private function getAllTables()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if (!in_array($tableName, $this->skipTables)) {
                $tableNames[] = $tableName;
            }
        }
        
        sort($tableNames);
        return $tableNames;
    }

    /**
     * Generate Eloquent Models for all tables
     */
    private function generateModels($tables)
    {
        $this->info("\n🏗️  Generating Eloquent Models...");
        
        foreach ($tables as $table) {
            $this->generateModel($table);
        }
    }

    /**
     * Generate a single Eloquent Model
     */
    private function generateModel($table)
    {
        $modelName = $this->getModelName($table);
        $columns = $this->getTableColumns($table);
        $foreignKeys = $this->getForeignKeys($table);
        
        $fillable = $this->getFillableFields($columns);
        $casts = $this->getCasts($columns);
        $relationships = $this->generateRelationships($table, $foreignKeys);
        
        $modelContent = $this->generateModelContent($modelName, $table, $fillable, $casts, $relationships);
        
        $filePath = app_path("Models/{$modelName}.php");
        File::put($filePath, $modelContent);
        
        $this->line("   ✅ {$modelName}");
    }

    /**
     * Convert table name to model name
     */
    private function getModelName($table)
    {
        // Handle Arabic table names
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $table)) {
            // For Arabic tables, use a descriptive English name
            $arabicToEnglish = [
                'قيود_الزواج' => 'MarriageEntry',
                'قيود_الوكالات' => 'AgencyEntry',
                'قيود_المبيع' => 'SaleEntry',
                'قيود_الطلاق' => 'DivorceEntry',
                'قيود_التصرفات' => 'DisposalEntry',
                'قيود_القسمة' => 'PartitionEntry',
                'قيود_الرجعة' => 'ReconciliationEntry',
            ];
            
            return $arabicToEnglish[$table] ?? Str::studly('legacy_' . $table);
        }
        
        return Str::studly(Str::singular($table));
    }

    /**
     * Get table columns information
     */
    private function getTableColumns($table)
    {
        return DB::select("DESCRIBE `{$table}`");
    }

    /**
     * Get foreign key constraints for a table
     */
    private function getForeignKeys($table)
    {
        try {
            $foreignKeys = DB::select("
                SELECT 
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '{$table}' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            return $foreignKeys;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get fillable fields for the model
     */
    private function getFillableFields($columns)
    {
        $fillable = [];
        
        foreach ($columns as $column) {
            // Skip auto-increment primary keys and timestamps
            if ($column->Extra === 'auto_increment' || 
                in_array($column->Field, ['created_at', 'updated_at', 'id'])) {
                continue;
            }
            
            $fillable[] = "'{$column->Field}'";
        }
        
        return $fillable;
    }

    /**
     * Get casts for model fields
     */
    private function getCasts($columns)
    {
        $casts = [];
        
        foreach ($columns as $column) {
            $field = $column->Field;
            $type = $column->Type;
            
            if (in_array($field, ['created_at', 'updated_at'])) {
                continue;
            }
            
            $cast = $this->mapColumnTypeToCast($type);
            if ($cast) {
                $casts[] = "'{$field}' => {$cast}";
            }
        }
        
        return $casts;
    }

    /**
     * Map MySQL column type to Laravel cast
     */
    private function mapColumnTypeToCast($type)
    {
        if (strpos($type, 'int') !== false) {
            return 'integer';
        } elseif (strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
            return 'decimal:2';
        } elseif (strpos($type, 'date') !== false) {
            return 'date';
        } elseif (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
            return 'datetime';
        } elseif (strpos($type, 'tinyint(1)') !== false) {
            return 'boolean';
        } elseif (strpos($type, 'json') !== false || strpos($type, 'longtext') !== false) {
            return 'array';
        }
        
        return null;
    }

    /**
     * Generate relationships for the model
     */
    private function generateRelationships($table, $foreignKeys)
    {
        $relationships = [];
        
        // Generate belongsTo relationships
        foreach ($foreignKeys as $fk) {
            $relationName = $this->getRelationName($fk->REFERENCED_TABLE_NAME);
            $relatedModel = $this->getModelName($fk->REFERENCED_TABLE_NAME);
            $foreignKey = $fk->COLUMN_NAME;
            $ownerKey = $fk->REFERENCED_COLUMN_NAME;
            
            $relationships[] = "    public function {$relationName}()\n    {\n        return \$this->belongsTo({$relatedModel}::class, '{$foreignKey}', '{$ownerKey}');\n    }";
        }
        
        // Generate hasMany relationships (basic implementation)
        $hasManyRelations = $this->getHasManyRelations($table);
        foreach ($hasManyRelations as $relation) {
            $relationships[] = $relation;
        }
        
        return $relationships;
    }

    /**
     * Get relation name from table name
     */
    private function getRelationName($table)
    {
        if ($table === 'users') {
            return 'user';
        } elseif ($table === 'notaries') {
            return 'notary';
        } elseif ($table === 'entries') {
            return 'entry';
        } elseif ($table === 'registers') {
            return 'register';
        } elseif ($table === 'contract_types') {
            return 'contractType';
        } elseif ($table === 'register_types') {
            return 'registerType';
        } elseif ($table === 'administrative_units') {
            return 'administrativeUnit';
        } elseif ($table === 'writer_types') {
            return 'writerType';
        }
        
        return Str::singular($table);
    }

    /**
     * Get hasMany relationships (simplified)
     */
    private function getHasManyRelations($table)
    {
        $relations = [];
        
        // Common hasMany relationships
        $hasManyMap = [
            'users' => [
                'notaries' => 'notaries',
                'entries' => 'entriesAsWriter',
                'notifications' => 'notifications',
                'system_logs' => 'systemLogs',
            ],
            'notaries' => [
                'entries' => 'entriesAsNotary',
                'registers' => 'registers',
            ],
            'entries' => [
                'marriage_contracts' => 'marriageContract',
                'agency_contracts' => 'agencyContract',
                'sale_contracts' => 'saleContract',
                'divorce_attestations' => 'divorceAttestation',
                'disposal_contracts' => 'disposalContract',
                'partition_contracts' => 'partitionContract',
                'reconciliation_attestations' => 'reconciliationAttestation',
                'entry_financial_data' => 'financialData',
            ],
            'contract_types' => [
                'entries' => 'entries',
                'fee_settings' => 'feeSettings',
                'fine_settings' => 'fineSettings',
            ],
            'registers' => [
                'entries' => 'entries',
            ],
        ];
        
        if (isset($hasManyMap[$table])) {
            foreach ($hasManyMap[$table] as $relatedTable => $relationName) {
                $relatedModel = $this->getModelName($relatedTable);
                $foreignKey = Str::singular($table) . '_id';
                
                $relations[] = "    public function {$relationName}()\n    {\n        return \$this->hasMany({$relatedModel}::class, '{$foreignKey}');\n    }";
            }
        }
        
        return $relations;
    }

    /**
     * Generate model content
     */
    private function generateModelContent($modelName, $table, $fillable, $casts, $relationships)
    {
        $fillableStr = implode(', ', $fillable);
        $castsStr = empty($casts) ? '' : implode(', ', $casts);
        $relationshipsStr = empty($relationships) ? '' : "\n\n" . implode("\n\n", $relationships);
        
        return "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for table: {$table}
 * 
 * @property int \$id
 * @property \Carbon\Carbon \$created_at
 * @property \Carbon\Carbon \$updated_at
 */
class {$modelName} extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected \$table = '{$table}';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected \$fillable = [
        {$fillableStr}
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected \$casts = [{$castsStr}];{$relationshipsStr}
}
";
    }

    /**
     * Generate Filament Resources for main tables
     */
    private function generateFilamentResources($tables)
    {
        $this->info("\n🎨 Generating Filament Resources...");
        
        // Create Resources directory if it doesn't exist
        $resourceDir = app_path('Filament/Resources');
        if (!File::exists($resourceDir)) {
            File::makeDirectory($resourceDir, 0755, true);
        }
        
        foreach ($tables as $table) {
            $this->generateFilamentResource($table);
        }
    }

    /**
     * Generate a single Filament Resource
     */
    private function generateFilamentResource($table)
    {
        $modelName = $this->getModelName($table);
        $resourceName = $modelName . 'Resource';
        $columns = $this->getTableColumns($table);
        
        $formFields = $this->generateFormFields($table, $columns);
        $tableColumns = $this->generateTableColumns($columns);
        
        $resourceContent = $this->generateResourceContent($resourceName, $modelName, $formFields, $tableColumns);
        
        $filePath = app_path("Filament/Resources/{$resourceName}.php");
        File::put($filePath, $resourceContent);
        
        $this->line("   ✅ {$resourceName}");
    }

    /**
     * Generate form fields for Filament resource
     */
    private function generateFormFields($table, $columns)
    {
        $fields = [];
        
        foreach ($columns as $column) {
            $field = $column->Field;
            
            // Skip auto-increment and timestamp fields
            if ($column->Extra === 'auto_increment' || 
                in_array($field, ['created_at', 'updated_at', 'id'])) {
                continue;
            }
            
            $fieldDefinition = $this->generateFormField($field, $column);
            if ($fieldDefinition) {
                $fields[] = $fieldDefinition;
            }
        }
        
        return implode("\n            ", $fields);
    }

    /**
     * Generate a single form field
     */
    private function generateFormField($field, $column)
    {
        $type = $column->Type;
        $label = $this->getFieldLabel($field);
        
        // Determine field type based on column type
        if (strpos($field, 'password') !== false) {
            return "Forms\Components\TextInput::make('{$field}')->password()->label('{$label}'),";
        } elseif (strpos($field, 'email') !== false) {
            return "Forms\Components\TextInput::make('{$field}')->email()->label('{$label}'),";
        } elseif (strpos($field, 'phone') !== false) {
            return "Forms\Components\TextInput::make('{$field}')->tel()->label('{$label}'),";
        } elseif (strpos($type, 'text') !== false || strpos($type, 'longtext') !== false) {
            return "Forms\Components\Textarea::make('{$field}')->label('{$label}'),";
        } elseif (strpos($type, 'date') !== false) {
            return "Forms\Components\DatePicker::make('{$field}')->label('{$label}'),";
        } elseif (strpos($type, 'int') !== false) {
            return "Forms\Components\TextInput::make('{$field}')->numeric()->label('{$label}'),";
        } elseif (strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
            return "Forms\Components\TextInput::make('{$field}')->numeric()->step(0.01)->label('{$label}'),";
        } elseif (strpos($type, 'tinyint(1)') !== false) {
            return "Forms\Components\Toggle::make('{$field}')->label('{$label}'),";
        } elseif (strpos($type, 'enum') !== false) {
            $options = $this->getEnumOptions($column->Type);
            return "Forms\Components\Select::make('{$field}')->options({$options})->label('{$label}'),";
        } else {
            return "Forms\Components\TextInput::make('{$field}')->label('{$label}'),";
        }
    }

    /**
     * Generate table columns for Filament resource
     */
    private function generateTableColumns($columns)
    {
        $tableColumns = [];
        
        foreach ($columns as $column) {
            $field = $column->Field;
            
            // Skip some fields for table view
            if (in_array($field, ['password', 'remember_token'])) {
                continue;
            }
            
            $columnDefinition = $this->generateTableColumn($field, $column);
            if ($columnDefinition) {
                $tableColumns[] = $columnDefinition;
            }
        }
        
        return implode("\n            ", $tableColumns);
    }

    /**
     * Generate a single table column
     */
    private function generateTableColumn($field, $column)
    {
        $label = $this->getFieldLabel($field);
        $type = $column->Type;
        
        if (strpos($field, 'email') !== false) {
            return "Tables\Columns\TextColumn::make('{$field}')->label('{$label}')->searchable(),";
        } elseif (strpos($type, 'date') !== false) {
            return "Tables\Columns\TextColumn::make('{$field}')->label('{$label}')->date()->sortable(),";
        } elseif (strpos($type, 'decimal') !== false || strpos($type, 'double') !== false || strpos($type, 'float') !== false) {
            return "Tables\Columns\TextColumn::make('{$field}')->label('{$label}')->money('SAR')->sortable(),";
        } elseif (strpos($type, 'tinyint(1)') !== false) {
            return "Tables\Columns\IconColumn::make('{$field}')->label('{$label}')->boolean(),";
        } else {
            return "Tables\Columns\TextColumn::make('{$field}')->label('{$label}')->searchable()->sortable(),";
        }
    }

    /**
     * Get field label in Arabic
     */
    private function getFieldLabel($field)
    {
        $labels = [
            'id' => 'المعرف',
            'name_ar' => 'الاسم',
            'name_en' => 'الاسم (إنجليزي)',
            'first_name_ar' => 'الاسم الأول',
            'second_name_ar' => 'اسم الأب',
            'third_name_ar' => 'اسم الجد',
            'fourth_name_ar' => 'اللقب',
            'full_name_ar' => 'الاسم الكامل',
            'email' => 'البريد الإلكتروني',
            'phone_number' => 'رقم الهاتف',
            'address' => 'العنوان',
            'notes' => 'ملاحظات',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
            'is_active' => 'نشط',
            'status' => 'الحالة',
            'description' => 'الوصف',
            'document_hijri_date' => 'تاريخ الوثيقة (هجري)',
            'document_gregorian_date' => 'تاريخ الوثيقة (ميلادي)',
            'husband_name' => 'اسم الزوج',
            'wife_name' => 'اسم الزوجة',
            'seller_name' => 'اسم البائع',
            'buyer_name' => 'اسم المشتري',
            'principal_name' => 'اسم الموكل',
            'agent_name' => 'اسم الوكيل',
            'item_value' => 'قيمة المبيع',
            'base_fees_amount' => 'مبلغ الرسوم الأساسية',
            'total_collected_amount' => 'المبلغ الإجمالي',
        ];
        
        return $labels[$field] ?? $field;
    }

    /**
     * Get enum options for select field
     */
    private function getEnumOptions($type)
    {
        // Extract enum values from type string
        preg_match("/enum\((.*)\)/", $type, $matches);
        if (isset($matches[1])) {
            $values = str_getcsv($matches[1], ',', "'");
            $options = [];
            foreach ($values as $value) {
                $options[] = "'{$value}' => '{$value}'";
            }
            return '[' . implode(', ', $options) . ']';
        }
        
        return '[]';
    }

    /**
     * Generate Filament resource content
     */
    private function generateResourceContent($resourceName, $modelName, $formFields, $tableColumns)
    {
        $pluralModelName = Str::plural($modelName);
        $modelClass = "\\App\\Models\\{$modelName}";
        
        return "<?php

namespace App\Filament\Resources;

use App\Filament\Resources\\{$resourceName}\\Pages;
use App\Filament\Resources\\{$resourceName}\\RelationManagers;
use {$modelClass};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class {$resourceName} extends Resource
{
    protected static ?string \$model = {$modelClass}::class;

    protected static ?string \$navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string \$navigationGroup = 'إدارة البيانات';

    protected static ?int \$navigationSort = 1;

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                {$formFields}
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                {$tableColumns}
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\List{$pluralModelName}::route('/'),
            'create' => Pages\Create{$modelName}::route('/create'),
            'view' => Pages\View{$modelName}::route('/{record}'),
            'edit' => Pages\Edit{$modelName}::route('/{record}/edit'),
        ];
    }
}
";
    }
}
