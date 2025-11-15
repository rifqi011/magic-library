<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Rifqi Banu Safingi',
            'email' => 'rifqi@example.com',
            'password' => bcrypt('123456789'),
            'role' => 'superadmin'
        ]);

        User::factory()->create([
            'name' => 'Diva Nur Anggraeni',
            'email' => 'diva@example.com',
            'password' => bcrypt('123456789'),
            'role' => 'admin'
        ]);

        $this->call([
            MemberSeeder::class,
            CategorySeeder::class,
            GenreSeeder::class,
            BookSeeder::class,
            BorrowingSeeder::class
        ]);
    }
}
