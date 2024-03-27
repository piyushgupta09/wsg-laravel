<?php

namespace Fpaipl\Panel\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MakeNewModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panel:make {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will setup and initialize a new module setup for given name.';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws ProcessFailedException
     */
    public function handle()
    {
        // Initialize the progress bar
        $this->info("\nInitializing module...");
        $progressBar = $this->output->createProgressBar(10);
        $progressBar->advance();

        // Process module name
        $module = $this->argument('module');
        $module = Str::lower($module);
        $Module = Str::ucfirst($module);
        $modulePath = base_path("modules/{$module}");

        // Create directories
        File::makeDirectory($modulePath, 0777, true, true);
        File::makeDirectory("{$modulePath}/src", 0777, true);
        File::makeDirectory("{$modulePath}/routes", 0777, true);
        File::makeDirectory("{$modulePath}/config", 0777, true);
        File::makeDirectory("{$modulePath}/database/migrations", 0777, true);
        File::makeDirectory("{$modulePath}/database/seeders", 0777, true);

        $this->info("\nDirectories created.");
        $progressBar->advance();

        // Initialize composer for module
        $composerInitCommand = [
            'composer',
            'init',
            '--name=fpaipl/' . $module,
            '--description=' . $Module . ' module for fpaipl',
            '--type=library',
            '--author=Piyush Gupta <pg.softcode@gmail.com>',
            '--license=Unlicensed',
            '--require=laravel/framework:^8.0'
        ];
        
        $process = new Process($composerInitCommand, $modulePath);
        $process->run();
        $progressBar->advance();
        $this->info("\nComposer install process started...");

        // Check the process success
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Add "minimum-stability" to composer.json
        $composerJsonPath = $modulePath . '/composer.json';
        $composerJsonData = json_decode(file_get_contents($composerJsonPath), true);
        $composerJsonData['minimum-stability'] = 'dev';
        file_put_contents($composerJsonPath, json_encode($composerJsonData, JSON_PRETTY_PRINT));
        $progressBar->advance();

        // Execute composer install
        $composerInstallCommand = ['composer', 'install'];
        $installProcess = new Process($composerInstallCommand, $modulePath);
        $installProcess->run();
        $progressBar->advance();
        $this->info("\nComposer install done.");

        // Check if the composer install process was successful
        if (!$installProcess->isSuccessful()) {
            throw new ProcessFailedException($installProcess);
        }

        // Step 1: Register the module in main composer.json
        $this->registerModuleInMainComposerJson($Module, $module);
        $this->info("\nModule registered in main composer.json");
        $progressBar->advance();

        // Step 2: Generate files
        File::put("{$modulePath}/routes/web.php", $this->getWebRoutesContent());
        File::put("{$modulePath}/config/{$module}.php", $this->getConfigContent($Module));
        File::put("{$modulePath}/database/seeders/{$Module}DatabaseSeeder.php", $this->getSeederContent($Module));
        File::put("{$modulePath}/src/{$Module}ServiceProvider.php", $this->getServiceProviderContent($Module, $module));
        $this->info("\nFiles generated.");
        $progressBar->advance();

        // Initialize git repository
        (new Process(['git', 'init'], $modulePath))->mustRun();
        (new Process(['git', 'add', '.'], $modulePath))->mustRun();
        (new Process(['git', 'commit', '-m', 'Initial commit'], $modulePath))->mustRun();
        $this->info("\nGit repository initialized.");
        $progressBar->advance();

        // Step 3: Register the ServiceProvider
        $this->registerServiceProvider($Module);
        $this->info("\nServiceProvider registered in config/app.php");
        $progressBar->advance();

        // Advance progress bar to finish
        $progressBar->finish();
        
        // Output success message
        $this->info("\nModule has been created.");
    }

    /**
     * Register the module in main composer.json
     *
     * @param string $module
     * @return void
     */
    private function registerModuleInMainComposerJson($Module, $module)
    {
        Log::info('Inside registerModuleInMainComposerJson method');
        
        $mainComposerJsonPath = base_path('composer.json');
        if (!file_exists($mainComposerJsonPath)) {
            $this->info("\ncomposer.json not found.");
            return;
        }

        $mainComposerJsonData = json_decode(file_get_contents($mainComposerJsonPath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->info("\nFailed to decode composer.json");
            return;
        }

        $mainComposerJsonData['repositories'][] = [
            "type" => "path",
            "url" => "modules/{$module}"
        ];

        $mainComposerJsonData['autoload']['psr-4']["Fpaipl\\{$Module}\\"] = "modules/{$module}/src/";
        $mainComposerJsonData['autoload']['psr-4']["Fpaipl\\{$Module}\\Database\\Seeders\\"] = "modules/{$module}/database/seeders/";

        file_put_contents($mainComposerJsonPath, json_encode($mainComposerJsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Register the ServiceProvider
     *
     * @param string $Module
     * @return void
     */
    private function registerServiceProvider($Module)
    {
        $configAppPath = config_path('app.php');
        if (!file_exists($configAppPath)) {
            $this->info("\napp.php not found.");
            return;
        }
        Log::info('Inside registerServiceProvider method');
        Log::info($configAppPath);
        $configAppData = file_get_contents($configAppPath);
        $newProvider = "Fpaipl\\{$Module}\\{$Module}ServiceProvider::class,";

        if (strpos($configAppData, $newProvider) === false) {
            $pattern = "/(Fpaipl\\\\Panel\\\\PanelServiceProvider::class,)/";
            $newConfigAppData = preg_replace($pattern, "$1\n        {$newProvider}", $configAppData);

            if (file_put_contents($configAppPath, $newConfigAppData) === false) {
                $this->info("\nFailed to write to app.php");
            } else {
                $this->info("\nSuccessfully wrote to app.php");
            }
        } else {
            $this->info("\nProvider already exists in app.php");
        }
    }


    /**
     * Get web routes content.
     *
     * @return string
     */
    private function getWebRoutesContent()
    {
        return <<<'EOL'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('panel::welcome');
});
EOL;
    }

    /**
     * Get configuration content.
     *
     * @param string $moduleName
     * @return string
     */
    private function getConfigContent($moduleName)
    {
        return <<<EOL
<?php

return [
    'name' => '{$moduleName}',
    'description' => '{$moduleName} module for fpaipl',
];
EOL;
    }

    /**
     * Get Seeder content.
     *
     * @param string $moduleName
     * @return string
     */
    private function getSeederContent($moduleName)
    {
        return <<<EOL
<?php

namespace Fpaipl\\{$moduleName}\\Database\\Seeders;

use Illuminate\\Database\\Seeder;

class {$moduleName}DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \$this->call([]);
    }
}
EOL;
    }
    
    /**
     * Get ServiceProvider content.
     *
     * @param string $ModuleName
     * @param string $moduleName
     * @return string
     */
    private function getServiceProviderContent($ModuleName, $moduleName)
    {
        return <<<EOL
<?php

namespace Fpaipl\\{$ModuleName};

use Illuminate\\Support\\ServiceProvider;

class {$ModuleName}ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        \$this->loadViewsFrom(__DIR__.'/resources/views', '{$moduleName}');
        \$this->loadViewComponentsAs('{$moduleName}', []);
        \$this->publishes([
            __DIR__.'/../config/{$moduleName}.php' => config_path('{$moduleName}.php'),
        ]);
    }
}
EOL;
    }
}
