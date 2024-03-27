<?php

namespace Fpaipl\Shopy\Database\Seeders;

use Illuminate\Database\Seeder;
use Fpaipl\Shopy\Database\Seeders\DatasetSeeder;

class ShopyDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DatasetSeeder::class);
    }
}
