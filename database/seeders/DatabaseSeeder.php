<?php

namespace Database\Seeders;

use App\Models\Train;
use App\Models\Vagon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Train::factory(3)->create();
        Vagon::factory(6)->create();
    }
}
