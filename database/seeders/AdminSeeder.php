<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti email & password ini setelah login pertama.
        User::firstOrCreate(
            ['email' => 'admin@sman1nusantara.sch.id'],
            ['name' => 'Admin Sarpras', 'password' => 'Admin12345', 'is_active' => true]
        );
    }
}
