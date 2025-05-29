<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sauce;

class SauceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Sauce::create(['name' => 'Sambal Terasi', 'extra_price' => 2000]);
        Sauce::create(['name' => 'Sambal Bangkok', 'extra_price' => 2000]);
        Sauce::create(['name' => 'Chili Oil', 'extra_price' => 2000]);
    }
}
