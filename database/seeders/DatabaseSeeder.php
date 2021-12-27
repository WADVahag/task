<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $articles = Article::factory(5)->create();
        $tags = Tag::factory(10)->create();

        $articles->each(function (Article $article) use ($tags) {
            $article->tags()->sync($tags->shuffle()->take(3)->pluck('id'));
        });
    }
}
