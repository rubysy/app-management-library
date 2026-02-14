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
        $categories = [
            ['name' => 'Fiksi', 'slug' => 'fiksi', 'description' => 'Novel, cerpen, dan karya fiksi lainnya'],
            ['name' => 'Non-Fiksi', 'slug' => 'non-fiksi', 'description' => 'Buku berbasis fakta dan informasi'],
            ['name' => 'Sains', 'slug' => 'sains', 'description' => 'Buku tentang ilmu pengetahuan dan teknologi'],
            ['name' => 'Sejarah', 'slug' => 'sejarah', 'description' => 'Buku tentang peristiwa sejarah'],
            ['name' => 'Biografi', 'slug' => 'biografi', 'description' => 'Kisah hidup tokoh-tokoh terkenal'],
            ['name' => 'Fantasi', 'slug' => 'fantasi', 'description' => 'Cerita fantasi dan dunia imajinatif'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'description' => 'Buku tentang teknologi dan programming'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Buku pelajaran dan referensi akademik'],
            ['name' => 'Agama', 'slug' => 'agama', 'description' => 'Buku religius dan spiritualitas'],
            ['name' => 'Komik', 'slug' => 'komik', 'description' => 'Komik, manga, dan graphic novel'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
