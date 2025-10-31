<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixFilamentResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-filament-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Filament Resource class inheritance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing Filament Resources...');

        $resourceFiles = glob(app_path('Filament/Resources/*.php'));
        $fixedCount = 0;

        foreach ($resourceFiles as $file) {
            $content = File::get($file);
            
            // Fix the Resource class inheritance
            $originalContent = $content;
            
            // Replace incorrect Resource with correct one for all resource classes
            $content = preg_replace(
                '/class (\w+)Resource extends Resource/',
                'class $1Resource extends \Filament\Resources\Resource',
                $content
            );
            
            // Only write if content changed
            if ($content !== $originalContent) {
                File::put($file, $content);
                $resourceName = basename($file, '.php');
                $this->line("   ✅ Fixed {$resourceName}");
                $fixedCount++;
            }
        }

        $this->info("\n✅ Fixed {$fixedCount} resource files");
    }
}
