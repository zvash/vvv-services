<?php

namespace Database\Seeders;

use App\Models\ServerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServerType::query()
            ->firstOrCreate(['title' => 'v2ray']);
        ServerType::query()
            ->firstOrCreate(['title' => 'open_vpn']);
        ServerType::query()
            ->firstOrCreate(['title' => 'outline']);
    }
}
