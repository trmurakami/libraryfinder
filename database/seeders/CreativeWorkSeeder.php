<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreativeWork;

class CreativeWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreativeWork::factory()
        ->count(50)
        ->create();
    }
}
