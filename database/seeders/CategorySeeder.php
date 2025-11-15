<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Novel',
                'slug' => 'novel',
                'description' => 'Buku fiksi naratif panjang yang mengisahkan kehidupan karakter dan peristiwa',
                'status' => 'active'
            ],
            [
                'name' => 'Manga',
                'slug' => 'manga',
                'description' => 'Komik atau novel grafis yang berasal dari Jepang',
                'status' => 'active'
            ],
            [
                'name' => 'Buku Sekolah',
                'slug' => 'buku-sekolah',
                'description' => 'Buku pelajaran dan referensi untuk pendidikan formal',
                'status' => 'active'
            ],
            [
                'name' => 'Biografi',
                'slug' => 'biografi',
                'description' => 'Kisah hidup seseorang yang ditulis oleh orang lain',
                'status' => 'active'
            ],
            [
                'name' => 'Self Improvement',
                'slug' => 'self-improvement',
                'description' => 'Buku pengembangan diri dan motivasi',
                'status' => 'active'
            ],
            [
                'name' => 'Ensiklopedia',
                'slug' => 'ensiklopedia',
                'description' => 'Buku referensi yang berisi informasi tentang berbagai topik',
                'status' => 'active'
            ],
            [
                'name' => 'Komik',
                'slug' => 'komik',
                'description' => 'Cerita bergambar lokal Indonesia',
                'status' => 'active'
            ],
            [
                'name' => 'Majalah',
                'slug' => 'majalah',
                'description' => 'Publikasi berkala yang berisi artikel dan informasi',
                'status' => 'active'
            ],
            [
                'name' => 'Kamus',
                'slug' => 'kamus',
                'description' => 'Buku referensi yang berisi daftar kata dan maknanya',
                'status' => 'active'
            ],
            [
                'name' => 'Antologi',
                'slug' => 'antologi',
                'description' => 'Kumpulan karya sastra dari berbagai penulis',
                'status' => 'active'
            ],
        ];

        DB::table('categories')->insert($categories);

        $this->command->info('Categories seeded successfully!');
    }
}
