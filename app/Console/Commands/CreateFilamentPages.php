<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateFilamentPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-filament-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Filament Pages for all generated Resources';

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
        $this->info('🚀 Creating Filament Pages for all Resources...');
        
        foreach ($this->mainTables as $table) {
            $this->createPagesForTable($table);
        }
        
        $this->info("\n✅ All Filament Pages created successfully!");
        $this->info("📁 Pages created in: app/Filament/Resources/{ResourceName}/Pages/");
    }

    /**
     * Create pages for a specific table
     */
    private function createPagesForTable($table)
    {
        $modelName = $this->getModelName($table);
        $resourceName = $modelName . 'Resource';
        $pluralModelName = Str::plural($modelName);
        
        $this->info("\n📝 Creating pages for {$resourceName}...");
        
        // Create Pages directory
        $pagesDir = app_path("Filament/Resources/{$resourceName}/Pages");
        if (!File::exists($pagesDir)) {
            File::makeDirectory($pagesDir, 0755, true);
        }
        
        // Create List page
        $this->createListPage($resourceName, $pluralModelName, $pagesDir);
        
        // Create Create page
        $this->createCreatePage($resourceName, $modelName, $pagesDir);
        
        // Create View page
        $this->createViewPage($resourceName, $modelName, $pagesDir);
        
        // Create Edit page
        $this->createEditPage($resourceName, $modelName, $pagesDir);
    }

    /**
     * Convert table name to model name
     */
    private function getModelName($table)
    {
        return Str::studly(Str::singular($table));
    }

    /**
     * Create List page
     */
    private function createListPage($resourceName, $pluralModelName, $pagesDir)
    {
        $content = "<?php

namespace App\\Filament\\Resources\\{$resourceName}\\Pages;

use App\\Filament\\Resources\\{$resourceName};
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class List{$pluralModelName} extends ListRecords
{
    protected static string \$resource = {$resourceName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }
}
";
        
        File::put("{$pagesDir}/List{$pluralModelName}.php", $content);
        $this->line("   ✅ List{$pluralModelName}");
    }

    /**
     * Create Create page
     */
    private function createCreatePage($resourceName, $modelName, $pagesDir)
    {
        $content = "<?php

namespace App\\Filament\\Resources\\{$resourceName}\\Pages;

use App\\Filament\\Resources\\{$resourceName};
use Filament\\Actions;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$modelName} extends CreateRecord
{
    protected static string \$resource = {$resourceName}::class;

    protected function getRedirectUrl(): string
    {
        return \$this->getResource()::getUrl('index');
    }
}
";
        
        File::put("{$pagesDir}/Create{$modelName}.php", $content);
        $this->line("   ✅ Create{$modelName}");
    }

    /**
     * Create View page
     */
    private function createViewPage($resourceName, $modelName, $pagesDir)
    {
        $content = "<?php

namespace App\\Filament\\Resources\\{$resourceName}\\Pages;

use App\\Filament\\Resources\\{$resourceName};
use Filament\\Actions;
use Filament\\Resources\\Pages\\ViewRecord;

class View{$modelName} extends ViewRecord
{
    protected static string \$resource = {$resourceName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\EditAction::make(),
        ];
    }
}
";
        
        File::put("{$pagesDir}/View{$modelName}.php", $content);
        $this->line("   ✅ View{$modelName}");
    }

    /**
     * Create Edit page
     */
    private function createEditPage($resourceName, $modelName, $pagesDir)
    {
        $content = "<?php

namespace App\\Filament\\Resources\\{$resourceName}\\Pages;

use App\\Filament\\Resources\\{$resourceName};
use Filament\\Actions;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{$modelName} extends EditRecord
{
    protected static string \$resource = {$resourceName}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return \$this->getResource()::getUrl('index');
    }
}
";
        
        File::put("{$pagesDir}/Edit{$modelName}.php", $content);
        $this->line("   ✅ Edit{$modelName}");
    }
}
