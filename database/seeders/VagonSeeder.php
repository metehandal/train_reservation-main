<?php

namespace Database\Seeders;

use App\Models\Vagon;
use Illuminate\Database\Seeder;

class VagonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vagon::factory()->count(6)->create();
    }
}
