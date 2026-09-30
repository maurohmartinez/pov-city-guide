<?php

namespace App\Observers;

use App\Jobs\GenerateImageSizes;
use App\Models\Article;
use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Storage;

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

    public function forceDeleted(Article $article): void
    {
        ImageProcessingService::deleteAllSizes(path: $article->image, disk: 'articles');

        $this->deleteContentImages($article);
    }

    /**
     * Remove images uploaded through the dynamic_content field (images rows),
     * across every translation, so they don't linger on disk after permanent deletion.
     */
    private function deleteContentImages(Article $article): void
    {
        $paths = [];

        foreach ($article->getTranslations('content') as $raw) {
            $rows = is_array($raw) ? $raw : json_decode((string) $raw, true);

            if (! is_array($rows)) {
                continue;
            }

            foreach ($rows as $row) {
                if (($row['type'] ?? null) !== 'images' || ! is_array($row['value'] ?? null)) {
                    continue;
                }

                foreach ($row['value'] as $path) {
                    if (is_string($path) && $path !== '') {
                        $paths[] = $path;
                    }
                }
            }
        }

        if ($paths !== []) {
            Storage::disk('articles')->delete(array_unique($paths));
        }
    }
}
