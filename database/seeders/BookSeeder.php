<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $fiksiId     = Category::where('slug', 'fiksi')->value('id');
    $nonfiksiId  = Category::where('slug', 'non-fiksi')->value('id');
    $selfHelpId  = Category::where('slug', 'self-help')->value('id');
    $sejarahId   = Category::where('slug', 'sejarah')->value('id');
    $sainsId     = Category::where('slug', 'sains')->value('id');
    $teknologiId = Category::where('slug', 'teknologi')->value('id');

    $categories = [
        $fiksiId,
        $nonfiksiId,
        $selfHelpId,
        $sejarahId,
        $sainsId,
        $teknologiId,
    ];

    $judulList = [
        'Laskar Pelangi', 'Atomic Habit', 'Sapiens',
        'Filosofi Teras', 'Ikigai',
    ];

    $penulisList = [
        'Ahmad Rizki', 'Budi Santoso', 'Citra Lestari',
        'Dewi Anggraini', 'Eko Prasetyo'
    ];

    $covers = [
    'covers/ah.jpeg',
    'covers/ikigai.jpeg',
    'covers/lp.jpeg',
    'covers/sapiens.jpeg',
    'covers/ft.jpg',
];


    for ($i = 1; $i <= 25; $i++) {

        $judul = $judulList[array_rand($judulList)] . " " . $i;

        Book::create([
            'judul' => $judul,
            'penulis' => $penulisList[array_rand($penulisList)],
            'category_id' => $categories[array_rand($categories)],
            'penerbit' => 'Penerbit ' . chr(64 + rand(1, 26)),
            'halaman' => rand(100, 500),
            'tahun_terbit' => rand(1990, 2024),
            'sinopsis' => 'Ini adalah sinopsis singkat untuk buku ' . $judul,
            'cover' => $covers[array_rand($covers)],
            'ebook' => null,
            'stok' => rand(1, 20),
            'isbn' => '978' . rand(1000000000, 9999999999),
            'is_active' => true,
        ]);
    }
}
}
