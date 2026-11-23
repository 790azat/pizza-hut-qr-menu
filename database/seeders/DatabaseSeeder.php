<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account comes from ADMIN_EMAIL / ADMIN_PASSWORD (only created if missing).
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        if (! User::where('email', $email)->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => env('ADMIN_PASSWORD', 'password'),
            ]);
        }

        if (Category::count() === 0) {
            $this->call(MenuSeeder::class);
        }
    }
}
