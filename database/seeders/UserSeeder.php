<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'username' => 'michael',
            'password' => Hash::make('12345'), 
            'name' => 'Michael'
        ]);
        User::create([
            'username' => 'miracle',
            'password' => Hash::make('12345'),
            'name' => 'Miracle'
        ]);
        User::create([
            'username' => 'clarion',
            'password' => Hash::make('12345'),
            'name' => 'Clarion'
        ]);
        User::create([
            'username' => 'jeffry',
            'password' => Hash::make('12345'),
            'name' => 'Jeffry'
        ]);
        User::create([
            'username' => 'fiola',
            'password' => Hash::make('12345'),
            'name' => 'Fiola'
        ]);
        User::create([
            'username' => 'richard',
            'password' => Hash::make('12345'),
            'name' => 'Richard'
        ]);
        User::create([
            'username' => 'michelle',
            'password' => Hash::make('12345'),
            'name' => 'Michelle'
        ]);
    }
}
