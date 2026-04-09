<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // ─── Categories ───────────────────────────────────────
    $categories = [
        ['nama' => 'Fiksi',      'deskripsi' => 'Novel dan cerita fiksi', 'color' => '#e53170'],
        ['nama' => 'Non-Fiksi',  'deskripsi' => 'Buku berbasis fakta dan pengetahuan', 'color' => '#001858'],
        ['nama' => 'Sejarah',    'deskripsi' => 'Buku sejarah Indonesia dan dunia', 'color' => '#55423d'],
        ['nama' => 'Self-Help',  'deskripsi' => 'Pengembangan diri', 'color' => '#FFFF33'],
        ['nama' => 'Sains',      'deskripsi' => 'Ilmu pengetahuan alam', 'color' => '#FF33FF'],
        ['nama' => 'Teknologi',  'deskripsi' => 'Komputer, programming, IT', 'color' => '#33FFFF'],
    ];

    foreach ($categories as $cat) {
        Category::create($cat); // slug auto-generate dari boot()
    }
    }
}
