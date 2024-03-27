<?php

namespace Fpaipl\Panel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDatabase extends Command
{
    protected $signature = 'db:clean {connection?}';
    protected $description = 'Clean the specified database connection by dropping all its tables.';

    public function handle()
    {
        $connectionName = $this->argument('connection') ?? config('database.default');
        $db = DB::connection($connectionName);
        $databaseName = $db->getDatabaseName();

        // Warning prompt
        if (!$this->confirm("Are you sure you want to drop all tables in the '$databaseName' database? This action cannot be undone.")) {
            return;
        }

        $tables = $db->select('SHOW TABLES');

        $db->statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $table) {
            $tableName = $table->{"Tables_in_$databaseName"};
            $db->statement("DROP TABLE `$tableName`");
        }
        $db->statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info("All tables in the '$databaseName' database have been dropped.");
    }

}
