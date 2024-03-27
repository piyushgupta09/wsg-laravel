<?php

namespace Fpaipl\Prody\Database\Seeders;

use Illuminate\Database\Seeder;
use Fpaipl\Prody\Database\Seeders\DummySeeder;
use Fpaipl\Prody\Database\Seeders\DatasetSeeder;

class ProdyDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
    */
    public function run(): void
    {
        $this->call(DatasetSeeder::class);
        $this->call(DummySeeder::class);
    }
}
