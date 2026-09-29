<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    use \App\Traits\HasImageSample;
    use \Illuminate\Database\Console\Seeds\WithoutModelEvents;

    public function run(): void
    {
        $this->addSampleImage('articles');

        Article::factory()->count(50)->create();
    }
}
