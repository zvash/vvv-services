<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::query()
            ->firstOrCreate([
                'name' => 'admin',
                'email' => 'admin@gouril.xyz',
                'password' => '$2y$10$e37l8UAHxrJjh0l7K6taJuduvjqe5heBds2qRz9VZryxBUDmt3Apu',
            ]);
    }
}
