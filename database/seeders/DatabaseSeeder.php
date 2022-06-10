<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SubjectsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SubjectsTableSeeder::class,
        ]);
        \App\Models\Teachers::factory(10)->create();
    }
}
