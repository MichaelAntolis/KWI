<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dumpling;

class DumplingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Dumpling::create(['name' => 'Goreng', 'price' => 18000]);
        Dumpling::create(['name' => 'Steam Fried', 'price' => 18000]);
    }
}
