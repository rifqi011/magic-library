<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Novel',
                'slug' => 'novel',
                'description' => 'Novel'
            ],
            [
                'name' => 'Manga',
                'slug' => 'manga',
                'description' => 'Manga'
            ],
            [
                'name' => 'Buku Sekolah',
                'slug' => 'buku-sekolah',
                'description' => 'Buku Sekolah'
            ]
        ]);
    }
}
