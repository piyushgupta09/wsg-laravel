<?php

namespace Fpaipl\Panel;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Fpaipl\Panel\Http\Livewire\AlertBox;
use Fpaipl\Panel\Http\Livewire\AppToast;
use Fpaipl\Panel\Http\Livewire\Datatables;
use Fpaipl\Panel\View\Components\BulkSelect;
use Fpaipl\Panel\Http\Livewire\Notifications;
use Fpaipl\Panel\Http\Livewire\AddSearchSelect;
use Fpaipl\Panel\Console\Commands\CleanDatabase;
use Fpaipl\Panel\View\Components\DependentModel;
use Fpaipl\Panel\Console\Commands\ModuleSeedCommand;
use Fpaipl\Panel\View\Components\Dashboard\Adminlte;
use Fpaipl\Panel\Console\Commands\CleanTempDirectory;
use Fpaipl\Panel\Console\Commands\DeleteModuleCommand;
use Fpaipl\Panel\Console\Commands\MakeNewModuleCommand;
use Fpaipl\Panel\View\Components\SelectedRecordsAlertBox;

class PanelServiceProvider extends ServiceProvider {

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
        $this->commands([
            ModuleSeedCommand::class,
            MakeNewModuleCommand::class,
            DeleteModuleCommand::class,
            CleanTempDirectory::class,
            CleanDatabase::class,
        ]);
        
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'panel');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewComponentsAs('panel', [
            Adminlte::class,
            BulkSelect::class,
            SelectedRecordsAlertBox::class,
            DependentModel::class,
        ]);
      
        Livewire::component('alert-box', AlertBox::class);
        Livewire::component('app-toast', AppToast::class);
        Livewire::component('datatables', Datatables::class);
        Livewire::component('notifications', Notifications::class);
        Livewire::component('add-search-select', AddSearchSelect::class);

        $this->publishes([
            __DIR__.'/../config/panel.php' => config_path('panel.php'),
            __DIR__.'/../config/settings.php' => config_path('settings.php'),
        ],'panel');
    }

}
