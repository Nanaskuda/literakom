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

        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
        ]);

    // ─── Admin ────────────────────────────────────────────
    User::create([
        'no_id'    => 'LIB-0000',
        'name'     => 'Administrator',
        'kelas'    => '12',
        'jurusan'  => 'RPL',
        'username' => 'admin',
        'email'    => 'admin@digiLitera.id',
        'password' => bcrypt('admin123'),
        'role'     => 'admin',

    ]);
    User::create([
        'no_id'    => 'LIB-0002',
        'name'     => 'pri',
        'kelas'    => '10',
        'jurusan'  => 'RPL',
        'username' => 'Apri',
        'email'    => 'apri@gmail.com',
        'password' => bcrypt('apri123'),
        'role'     => 'member',

    ]);
    }
}
