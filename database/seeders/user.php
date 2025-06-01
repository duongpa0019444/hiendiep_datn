<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelsUser::create([
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'password' => '11111111',
            'role' => 'admin'
        ]);
    }
}
