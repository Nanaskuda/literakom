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
          // ─── Books (pakai category_id sekarang) ───────────────
    $fiksiId     = Category::where('slug', 'fiksi')->value('id');
    $sejarahId   = Category::where('slug', 'sejarah')->value('id');
    $selfHelpId  = Category::where('slug', 'self-help')->value('id');

    $books = [
        ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata',  'category_id' => $fiksiId,    'tahun_terbit' => 2005, 'stok' => 3],
        ['judul' => 'Bumi Manusia',   'penulis' => 'Pramoedya A.T',  'category_id' => $sejarahId,  'tahun_terbit' => 1980, 'stok' => 2],
        ['judul' => 'Atomic Habits',  'penulis' => 'James Clear',    'category_id' => $selfHelpId, 'tahun_terbit' => 2018, 'stok' => 5],
        ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'category_id' => $selfHelpId, 'tahun_terbit' => 2018, 'stok' => 4, 'cover' => 'covers/01KNMB4T3C5BVKCSA31M0MWPGQ.jpg'],
        ['judul' => 'Sapiens',        'penulis' => 'Yuval Noah Harari', 'category_id' => $sejarahId,  'tahun_terbit' => 2011, 'stok' => 4],
        ['judul' => 'The Alchemist',  'penulis' => 'Paulo Coelho',   'category_id' => $fiksiId,    'tahun_terbit' => 1988, 'stok' => 6],
    ];

    foreach ($books as $book) {
            // Cek apakah category_id ditemukan sebelum insert
            if ($book['category_id']) {
                Book::create($book);
            }
        }
    }
}
