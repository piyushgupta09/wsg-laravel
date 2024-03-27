<?php

namespace Fpaipl\Panel\Console\Commands;

use Illuminate\Console\Command;

class ModuleSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panel:seed {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will run the seed command for the given module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->argument('module');

        $seederClass = "\\Fpaipl\\{$module}\\Database\\Seeders\\{$module}DatabaseSeeder";

        if (class_exists($seederClass)) {
            $this->call('db:seed', ['--class' => $seederClass]);
            $this->info($module . ' seeders executed successfully.');
        } else {
            $this->error("The {$seederClass} class does not exist.");
        }
    }
}
