<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixModelCasts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-model-casts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix model casts by adding quotes around cast values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing model casts...');

        $modelFiles = glob(app_path('Models/*.php'));
        $fixedCount = 0;

        foreach ($modelFiles as $file) {
            $content = File::get($file);
            
            // Fix the casts array by adding quotes
            $originalContent = $content;
            
            // Pattern to match unquoted cast values
            $content = preg_replace(
                "/'([^']+)'\s*=>\s*(integer|boolean|string|date|datetime|decimal:\d+|array)/",
                "'$1' => '$2'",
                $content
            );
            
            // Only write if content changed
            if ($content !== $originalContent) {
                File::put($file, $content);
                $modelName = basename($file, '.php');
                $this->line("   ✅ Fixed {$modelName}");
                $fixedCount++;
            }
        }

        $this->info("\n✅ Fixed casts in {$fixedCount} model files");
    }
}
