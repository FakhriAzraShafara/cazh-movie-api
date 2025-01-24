<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //make 5 user seeder
        User::create([
            'name'=>'Fakhri Azra',
            'email'=>'fakhrishafara@student.uns.ac.id',
            'password'=>Hash::make('1234'),
        ]);
        User::factory(4)->create();
    }
}
