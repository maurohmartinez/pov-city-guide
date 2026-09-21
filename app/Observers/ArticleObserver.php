<?php

namespace App\Observers;

use App\Jobs\GenerateImageSizes;
use App\Models\Article;
use App\Services\ImageProcessingService;

class ArticleObserver
{
    public function saved(Article $article): void
    {
        if ($article->wasChanged('image')) {
            ImageProcessingService::deleteAllSizes(path: $article->getOriginal('image'), disk: 'articles');
        }

        if (($article->wasRecentlyCreated || $article->wasChanged('image')) && $article->image) {
            GenerateImageSizes::dispatch(Article::class, $article->id)->afterCommit();
        }
    }

    public function deleted(Article $article): void
    {
        ImageProcessingService::deleteAllSizes(path: $article->image, disk: 'articles');
    }
}
