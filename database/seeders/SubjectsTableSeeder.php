<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subjects;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subjects::create(['name'=>'Maths']);
        Subjects::create(['name'=>'Science']);
        Subjects::create(['name'=>'History']);
    }
}
