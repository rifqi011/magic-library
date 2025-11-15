<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            // Novel Indonesia
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 1, // Novel
                'year' => 2005,
                'isbn' => '9789793062792',
                'stock' => 5,
                'description' => 'Novel tentang kehidupan anak-anak di Belitung',
                'synopsis' => 'Laskar Pelangi adalah novel pertama karya Andrea Hirata yang bercerita tentang kehidupan 10 anak dari keluarga miskin yang bersekolah di SD Muhammadiyah di Belitung. Novel ini mengangkat tema pendidikan, persahabatan, dan perjuangan.',
                'status' => 'active',
                'genres' => [1, 10], // Romance, Drama
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'category_id' => 1, // Novel
                'year' => 1980,
                'isbn' => '9789799101129',
                'stock' => 4,
                'description' => 'Novel sejarah Indonesia pada masa kolonial',
                'synopsis' => 'Bumi Manusia merupakan bagian pertama dari Tetralogi Buru yang mengisahkan perjalanan hidup Minke, seorang pribumi muda yang belajar di sekolah Belanda. Novel ini menggambarkan kondisi sosial dan politik Indonesia pada masa kolonial Belanda.',
                'status' => 'active',
                'genres' => [1, 8, 10], // Romance, History, Drama
            ],
            [
                'title' => 'Perahu Kertas',
                'author' => 'Dee Lestari',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 1, // Novel
                'year' => 2009,
                'isbn' => '9789793062853',
                'stock' => 6,
                'description' => 'Novel romantis tentang dua anak muda dengan mimpi berbeda',
                'synopsis' => 'Perahu Kertas berkisah tentang Kugy dan Keenan, dua anak muda dengan mimpi dan latar belakang berbeda. Kugy bermimpi menjadi penulis dongeng, sedangkan Keenan ingin menjadi pelukis. Novel ini mengisahkan perjalanan mereka mengejar mimpi dan cinta.',
                'status' => 'active',
                'genres' => [1, 13], // Romance, Slice of Life
            ],
            [
                'title' => 'Sang Pemimpi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 1, // Novel
                'year' => 2006,
                'isbn' => '9789793062808',
                'stock' => 5,
                'description' => 'Sekuel dari Laskar Pelangi',
                'synopsis' => 'Sang Pemimpi adalah novel kedua dari tetralogi Laskar Pelangi. Novel ini menceritakan kisah Ikal, Arai, dan Jimbron yang bermimpi melanjutkan pendidikan ke Prancis meskipun berasal dari keluarga miskin di Belitung.',
                'status' => 'active',
                'genres' => [1, 3, 7], // Romance, Philosophy, Adventure
            ],
            [
                'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1, // Novel
                'year' => 2009,
                'isbn' => '9789792248074',
                'stock' => 7,
                'description' => 'Novel inspiratif tentang kehidupan di pesantren',
                'synopsis' => 'Negeri 5 Menara mengisahkan perjalanan Alif Fikri dan empat temannya di Pondok Madani (PM) Ponorogo. Novel ini mengangkat tema pendidikan, persahabatan, dan semangat untuk meraih mimpi dengan motto "Man Jadda Wa Jadda" (siapa bersungguh-sungguh akan berhasil).',
                'status' => 'active',
                'genres' => [3, 7, 15], // Philosophy, Adventure, Spiritual
            ],
            [
                'title' => 'Hujan',
                'author' => 'Tere Liye',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1, // Novel
                'year' => 2016,
                'isbn' => '9786020822235',
                'stock' => 8,
                'description' => 'Novel tentang kehidupan dan takdir',
                'synopsis' => 'Hujan bercerita tentang Lail, seorang gadis yang kehilangan keluarganya dalam tragedi. Novel ini mengisahkan perjalanan hidupnya yang penuh lika-liku, persahabatan dengan Esok dan Maryam, serta bagaimana takdir mempertemukan mereka.',
                'status' => 'active',
                'genres' => [1, 10], // Romance, Drama
            ],
            [
                'title' => 'Pulang',
                'author' => 'Tere Liye',
                'publisher' => 'Republika Penerbit',
                'category_id' => 1, // Novel
                'year' => 2015,
                'isbn' => '9786027573659',
                'stock' => 6,
                'description' => 'Novel tentang pencarian jati diri',
                'synopsis' => 'Pulang mengisahkan Bujang, seorang anak jalanan yang tumbuh menjadi pengusaha sukses. Novel ini bercerita tentang perjalanan hidupnya mencari arti keluarga, cinta, dan pulang ke tempat di mana hatinya berada.',
                'status' => 'active',
                'genres' => [1, 3, 10], // Romance, Philosophy, Drama
            ],
            [
                'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'author' => 'Dee Lestari',
                'publisher' => 'Truedee Pustaka Sejati',
                'category_id' => 1, // Novel
                'year' => 2001,
                'isbn' => '9789799102744',
                'stock' => 4,
                'description' => 'Novel filosofis tentang cinta dan kehidupan',
                'synopsis' => 'Supernova adalah novel yang mengisahkan Ferre, seorang pria yang mencari makna hidup melalui seni dan cinta. Novel ini menggabungkan unsur filosofi, sains, dan spiritualitas dalam narasi yang unik.',
                'status' => 'active',
                'genres' => [1, 2, 3], // Romance, Science, Philosophy
            ],
            [
                'title' => 'Ronggeng Dukuh Paruk',
                'author' => 'Ahmad Tohari',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1, // Novel
                'year' => 1982,
                'isbn' => '9789792202854',
                'stock' => 3,
                'description' => 'Trilogi tentang kehidupan ronggeng di Jawa',
                'synopsis' => 'Ronggeng Dukuh Paruk mengisahkan Srintil, seorang gadis desa yang menjadi ronggeng (penari tradisional). Novel ini menggambarkan kehidupan masyarakat pedesaan Jawa, tradisi, dan konflik antara cinta dan takdir.',
                'status' => 'active',
                'genres' => [1, 8, 10], // Romance, History, Drama
            ],
            [
                'title' => 'Cantik itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1, // Novel
                'year' => 2002,
                'isbn' => '9789792294705',
                'stock' => 5,
                'description' => 'Novel realisme magis tentang sejarah Indonesia',
                'synopsis' => 'Cantik itu Luka adalah novel epik yang mengisahkan kehidupan Dewi Ayu dan keturunannya. Novel ini menggabungkan unsur realisme magis dengan sejarah Indonesia, mengangkat tema kolonialisme, kemerdekaan, dan masa Orde Baru.',
                'status' => 'active',
                'genres' => [1, 4, 8], // Romance, Fantasy, History
            ],
            [
                'title' => '5 cm',
                'author' => 'Donny Dhirgantoro',
                'publisher' => 'Gramedia Grasindo',
                'category_id' => 1, // Novel
                'year' => 2005,
                'isbn' => '9789792247657',
                'stock' => 8,
                'description' => 'Novel tentang persahabatan dan petualangan',
                'synopsis' => '5 cm mengisahkan petualangan lima sahabat - Arial, Riani, Zafran, Ian, dan Genta - yang memutuskan untuk tidak bertemu selama tiga bulan untuk merekatkan kembali persahabatan mereka. Novel ini mengangkat tema persahabatan, mimpi, dan petualangan mendaki Gunung Semeru.',
                'status' => 'active',
                'genres' => [7, 10, 13], // Adventure, Drama, Slice of Life
            ],
            [
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'category_id' => 1, // Novel
                'year' => 2017,
                'isbn' => '9786024246945',
                'stock' => 7,
                'description' => 'Novel tentang aktivis yang hilang pada masa Orde Baru',
                'synopsis' => 'Laut Bercerita mengisahkan kisah Biru Laut dan aktivis lainnya yang diculik dan hilang pada tahun 1998. Novel ini menceritakan perjuangan keluarga mencari kebenaran dan keadilan untuk orang-orang hilang, diceritakan dari dua sudut pandang: Laut yang hilang dan Asmara adiknya.',
                'status' => 'active',
                'genres' => [8, 10, 3], // History, Drama, Philosophy
            ],
            [
                'title' => 'Bakat Menggonggong',
                'author' => 'Mahfud Ikhwan',
                'publisher' => 'Mojok',
                'category_id' => 1, // Novel
                'year' => 2020,
                'isbn' => '9786237100126',
                'stock' => 6,
                'description' => 'Novel komedi satir tentang kehidupan kampus',
                'synopsis' => 'Bakat Menggonggong adalah novel komedi satir yang mengisahkan kehidupan Agus Merpati, seorang mahasiswa biasa-biasa saja yang terjebak dalam situasi absurd di kampusnya. Novel ini penuh dengan humor cerdas dan sindiran sosial tentang dunia akademis.',
                'status' => 'active',
                'genres' => [11, 13, 10], // Comedy, Slice of Life, Drama
            ],
            [
                'title' => 'Madilog: Materialisme, Dialektika, Logika',
                'author' => 'Tan Malaka',
                'publisher' => 'Narasi',
                'category_id' => 1, // Novel (filosofi)
                'year' => 2014,
                'isbn' => '9786021186206',
                'stock' => 4,
                'description' => 'Karya filosofis Tan Malaka tentang pemikiran dan logika',
                'synopsis' => 'Madilog adalah singkatan dari Materialisme, Dialektika, dan Logika. Buku ini merupakan karya monumental Tan Malaka yang membahas filsafat materialisme dialektik dan penerapannya dalam konteks perjuangan kemerdekaan Indonesia. Ditulis saat pengasingan, buku ini menunjukkan kedalaman pemikiran Tan Malaka.',
                'status' => 'active',
                'genres' => [3, 8, 14], // Philosophy, History, Educational
            ],

            // Manga
            [
                'title' => 'Naruto Vol. 1',
                'author' => 'Masashi Kishimoto',
                'publisher' => 'Elex Media Komputindo',
                'category_id' => 2, // Manga
                'year' => 2002,
                'isbn' => '9789797094409',
                'stock' => 10,
                'description' => 'Manga ninja populer dari Jepang',
                'synopsis' => 'Naruto adalah manga tentang seorang ninja muda bernama Uzumaki Naruto yang bermimpi menjadi Hokage, pemimpin desanya. Manga ini mengisahkan perjalanannya menjadi ninja yang kuat sambil mencari pengakuan dari orang-orang di sekitarnya.',
                'status' => 'active',
                'genres' => [7, 12, 4], // Adventure, Action, Fantasy
            ],
            [
                'title' => 'One Piece Vol. 1',
                'author' => 'Eiichiro Oda',
                'publisher' => 'Elex Media Komputindo',
                'category_id' => 2, // Manga
                'year' => 2000,
                'isbn' => '9789794334836',
                'stock' => 12,
                'description' => 'Manga petualangan bajak laut',
                'synopsis' => 'One Piece mengikuti petualangan Monkey D. Luffy yang bermimpi menjadi Raja Bajak Laut dengan menemukan harta karun legendaris One Piece. Bersama kru bajak lautnya, mereka menjelajahi lautan dan menghadapi berbagai tantangan.',
                'status' => 'active',
                'genres' => [7, 12, 11], // Adventure, Action, Comedy
            ],
            [
                'title' => 'Attack on Titan Vol. 1',
                'author' => 'Hajime Isayama',
                'publisher' => 'Kodansha Comics',
                'category_id' => 2, // Manga
                'year' => 2012,
                'isbn' => '9786020331461',
                'stock' => 8,
                'description' => 'Manga dark fantasy tentang titan',
                'synopsis' => 'Attack on Titan bercerita tentang dunia di mana manusia hidup di dalam tembok raksasa untuk melindungi diri dari Titan, makhluk humanoid pemakan manusia. Eren Yeager bersumpah membasmi semua Titan setelah ibunya dimakan.',
                'status' => 'active',
                'genres' => [4, 12, 6, 10], // Fantasy, Action, Horror, Drama
            ],

            // Self Improvement
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 5, // Self Improvement
                'year' => 2019,
                'isbn' => '9786020633176',
                'stock' => 10,
                'description' => 'Cara mudah dan terbukti untuk membentuk kebiasaan baik',
                'synopsis' => 'Atomic Habits mengajarkan bagaimana perubahan kecil dapat menghasilkan hasil yang luar biasa. James Clear menjelaskan sistem praktis untuk membentuk kebiasaan baik, menghilangkan kebiasaan buruk, dan menguasai perilaku kecil yang menghasilkan hasil luar biasa.',
                'status' => 'active',
                'genres' => [3, 14], // Philosophy, Educational
            ],
            [
                'title' => 'Berani Tidak Disukai',
                'author' => 'Ichiro Kishimi & Fumitake Koga',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 5, // Self Improvement
                'year' => 2019,
                'isbn' => '9786020503844',
                'stock' => 8,
                'description' => 'Buku tentang keberanian mengubah hidup',
                'synopsis' => 'Berdasarkan teori psikologi Alfred Adler, buku ini menjelaskan bagaimana kita dapat membebaskan diri dari trauma masa lalu dan ekspektasi orang lain untuk hidup dengan bebas dan bahagia.',
                'status' => 'active',
                'genres' => [3, 14], // Philosophy, Educational
            ],
            [
                'title' => 'Sebuah Seni untuk Bersikap Bodo Amat',
                'author' => 'Mark Manson (Terj. Inspire Creative Consultant)',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 5, // Self Improvement
                'year' => 2018,
                'isbn' => '9786020385723',
                'stock' => 8,
                'description' => 'Versi Indonesia dari The Subtle Art',
                'synopsis' => 'Buku ini mengajarkan bahwa tidak semua hal dalam hidup layak untuk dipikirkan. Mark Manson memberikan pandangan yang jujur dan lugas tentang bagaimana menjalani kehidupan yang lebih bermakna dengan fokus pada hal-hal yang benar-benar penting.',
                'status' => 'active',
                'genres' => [3, 14], // Philosophy, Educational
            ],
            [
                'title' => 'The Subtle Art of Not Giving a F*ck',
                'author' => 'Mark Manson',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 5, // Self Improvement
                'year' => 2018,
                'isbn' => '9786020385730',
                'stock' => 9,
                'description' => 'Pendekatan blak-blakan untuk hidup lebih baik',
                'synopsis' => 'Mark Manson menggunakan humor dan kejujuran brutal untuk menjelaskan bahwa kunci hidup yang lebih baik bukanlah menjadi positif sepanjang waktu, tetapi menjadi lebih baik dalam menghadapi tantangan.',
                'status' => 'active',
                'genres' => [3, 11], // Philosophy, Comedy
            ],

            // Karya Klasik
            [
                'title' => 'Moby Dick',
                'author' => 'Herman Melville',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 1, // Novel
                'year' => 2015,
                'isbn' => '9786020308913',
                'stock' => 4,
                'description' => 'Novel klasik Amerika tentang perburuan paus putih',
                'synopsis' => 'Moby Dick adalah kisah epik tentang Kapten Ahab yang terobsesi mengejar paus putih bernama Moby Dick yang telah merenggut kakinya. Diceritakan oleh Ishmael, seorang pelaut yang bergabung dengan kapal Pequod. Novel ini adalah mahakarya sastra Amerika yang mengeksplorasi tema obsesi, takdir, dan konfrontasi manusia dengan alam.',
                'status' => 'active',
                'genres' => [7, 3, 8], // Adventure, Philosophy, History
            ],

            // Biografi
            [
                'title' => 'Becoming',
                'author' => 'Michelle Obama',
                'publisher' => 'Gramedia Pustaka Utama',
                'category_id' => 4, // Biografi
                'year' => 2019,
                'isbn' => '9786020485294',
                'stock' => 5,
                'description' => 'Memoar Michelle Obama',
                'synopsis' => 'Dalam memoirnya yang sangat ditunggu-tunggu, mantan Ibu Negara Amerika Serikat ini mengajak pembaca masuk ke dalam dunianya, menceritakan pengalaman-pengalaman yang membentuk dirinya.',
                'status' => 'active',
                'genres' => [8, 14], // History, Educational
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'publisher' => 'Bentang Pustaka',
                'category_id' => 4, // Biografi
                'year' => 2015,
                'isbn' => '9786022914433',
                'stock' => 6,
                'description' => 'Biografi resmi Steve Jobs',
                'synopsis' => 'Berdasarkan lebih dari empat puluh wawancara dengan Steve Jobs dan lebih dari seratus wawancara dengan anggota keluarga, teman, rekan kerja, dan pesaingnya, buku ini menceritakan kisah roller-coaster dari kehidupan dan kepribadian yang sangat intens.',
                'status' => 'active',
                'genres' => [2, 8, 14], // Science, History, Educational
            ],
            [
                'title' => 'Dari Penjara ke Penjara',
                'author' => 'Tan Malaka',
                'publisher' => 'Narasi',
                'category_id' => 4, // Biografi
                'year' => 2018,
                'isbn' => '9786024811426',
                'stock' => 5,
                'description' => 'Otobiografi Tan Malaka, tokoh pergerakan Indonesia',
                'synopsis' => 'Dari Penjara ke Penjara adalah otobiografi Tan Malaka yang mengisahkan perjuangannya sebagai tokoh pergerakan kemerdekaan Indonesia. Buku ini mencatat perjalanan hidupnya yang penuh lika-liku, dari satu penjara ke penjara lain, sambil terus berjuang untuk kemerdekaan Indonesia.',
                'status' => 'active',
                'genres' => [8, 3, 14], // History, Philosophy, Educational
            ],
        ];

        foreach ($books as $bookData) {
            $genres = $bookData['genres'];
            unset($bookData['genres']);

            $bookData['slug'] = Str::slug($bookData['title']);

            $book = Book::create($bookData);

            // Attach genres
            $book->genres()->attach($genres);
        }

        $this->command->info('Books seeded successfully! Total: ' . count($books));
    }
}
