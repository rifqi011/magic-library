<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('genres')->insert([
            [
                'name' => 'Romance',
                'slug' => 'romance',
                'description' => 'Romance'
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Science Book'
            ],
            [
                'name' => 'Philosophy',
                'slug' => 'philosophy',
                'description' => 'Philosophy'
            ]
        ]);
    }
}
