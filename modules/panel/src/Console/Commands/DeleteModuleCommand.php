<?php

namespace Fpaipl\Panel\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteModuleCommand extends Command
{
    protected $signature = 'panel:delete {module}';
    protected $description = 'Deletes a module along with its associated files and directories.';

    public function handle()
    {
        $module = $this->argument('module');
        $module = Str::lower($module);
        $Module = Str::ucfirst($module);
        $modulePath = base_path("modules/{$module}");

        if (!File::exists($modulePath)) {
            $this->error("Module {$module} does not exist.");
            return;
        }

        if ($this->confirm("Do you really want to delete the {$Module} module? This action will delete ALL its content and is NOT recoverable.", false)) {
    
            // Unregister module from main composer.json
            $this->unregisterModuleInMainComposerJson($Module, $module);
        
            // Unregister ServiceProvider
            $this->unregisterServiceProvider($Module, $module);
        
            // Remove module directory
            File::deleteDirectory($modulePath);
        
            $this->info("Module {$Module} and all its contents have been deleted.");
            
        } else {
            $this->info("Module deletion cancelled.");
        }
        
    }

    private function unregisterModuleInMainComposerJson($Module, $module)
    {
        $mainComposerJsonPath = base_path('composer.json');
        $mainComposerJsonData = json_decode(file_get_contents($mainComposerJsonPath), true);

        // Remove repositories entry
        foreach ($mainComposerJsonData['repositories'] as $index => $repository) {
            if ($repository['url'] === "modules/{$module}") {
                unset($mainComposerJsonData['repositories'][$index]);
            }
        }

        // Remove psr-4 autoload entries
        unset(
            $mainComposerJsonData['autoload']['psr-4']["Fpaipl\\{$Module}\\"],
            $mainComposerJsonData['autoload']['psr-4']["Fpaipl\\{$Module}\\Database\\Seeders\\"]
        );

        // Re-index the array keys
        $mainComposerJsonData['repositories'] = array_values($mainComposerJsonData['repositories']);

        file_put_contents($mainComposerJsonPath, json_encode($mainComposerJsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function unregisterServiceProvider($Module, $module)
    {
        $configAppPath = config_path('app.php');
        if (!file_exists($configAppPath)) {
            $this->info("\napp.php not found.");
            return;
        }

        $configAppData = file_get_contents($configAppPath);
        $pattern = "/(Fpaipl\\\\{$Module}\\\\{$Module}ServiceProvider::class,)/";
        $newConfigAppData = preg_replace($pattern, "", $configAppData, -1, $count);

        if ($count > 0) {
            if (file_put_contents($configAppPath, $newConfigAppData) === false) {
                $this->info("\nFailed to write to app.php");
            } else {
                $this->info("\nSuccessfully wrote to app.php");
            }
        } else {
            $this->info("\nProvider not found in app.php");
        }
    }
}
