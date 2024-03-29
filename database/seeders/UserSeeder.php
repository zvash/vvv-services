<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'email' => config('app.admin_email'),
            ], [
                'name' => 'admin',
                'password' => Hash::make('admin'),
            ]);
    }
}
