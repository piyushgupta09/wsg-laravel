<?php

namespace Fpaipl\Panel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanTempDirectory extends Command
{
    protected $signature = 'clean:temp';
    protected $description = 'Cleans the temp directory in the public storage';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tempPath = public_path('storage/temp');

        if (File::exists($tempPath)) {
            File::cleanDirectory($tempPath);
            $this->info('The temp directory has been cleaned.');
        } else {
            $this->error('The temp directory does not exist.');
        }

        return 0;
    }
}
