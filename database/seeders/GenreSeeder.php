<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            [
                'name' => 'Romance',
                'slug' => 'romance',
                'description' => 'Cerita tentang hubungan romantis dan cinta',
                'status' => 'active'
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Buku tentang ilmu pengetahuan dan teknologi',
                'status' => 'active'
            ],
            [
                'name' => 'Philosophy',
                'slug' => 'philosophy',
                'description' => 'Pemikiran filosofis dan kebijaksanaan hidup',
                'status' => 'active'
            ],
            [
                'name' => 'Fantasy',
                'slug' => 'fantasy',
                'description' => 'Cerita fiksi dengan elemen magis dan dunia imajiner',
                'status' => 'active'
            ],
            [
                'name' => 'Mystery',
                'slug' => 'mystery',
                'description' => 'Cerita misteri, detektif, dan teka-teki',
                'status' => 'active'
            ],
            [
                'name' => 'Horror',
                'slug' => 'horror',
                'description' => 'Cerita menakutkan dan supernatural',
                'status' => 'active'
            ],
            [
                'name' => 'Adventure',
                'slug' => 'adventure',
                'description' => 'Cerita petualangan dan penjelajahan',
                'status' => 'active'
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Cerita atau fakta sejarah',
                'status' => 'active'
            ],
            [
                'name' => 'Thriller',
                'slug' => 'thriller',
                'description' => 'Cerita penuh ketegangan dan suspens',
                'status' => 'active'
            ],
            [
                'name' => 'Drama',
                'slug' => 'drama',
                'description' => 'Cerita tentang konflik emosional dan kehidupan',
                'status' => 'active'
            ],
            [
                'name' => 'Comedy',
                'slug' => 'comedy',
                'description' => 'Cerita lucu dan menghibur',
                'status' => 'active'
            ],
            [
                'name' => 'Action',
                'slug' => 'action',
                'description' => 'Cerita dengan adegan aksi dan pertarungan',
                'status' => 'active'
            ],
            [
                'name' => 'Slice of Life',
                'slug' => 'slice-of-life',
                'description' => 'Cerita tentang kehidupan sehari-hari',
                'status' => 'active'
            ],
            [
                'name' => 'Educational',
                'slug' => 'educational',
                'description' => 'Buku edukatif dan pembelajaran',
                'status' => 'active'
            ],
            [
                'name' => 'Spiritual',
                'slug' => 'spiritual',
                'description' => 'Buku tentang spiritualitas dan keagamaan',
                'status' => 'active'
            ],
        ];

        DB::table('genres')->insert($genres);

        $this->command->info('Genres seeded successfully!');
    }
}
