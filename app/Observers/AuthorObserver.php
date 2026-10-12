<?php

namespace App\Observers;

use App\Jobs\GenerateImageSizes;
use App\Models\Author;
use App\Services\ImageProcessingService;

class AuthorObserver
{
    public function saved(Author $author): void
    {
        if ($author->wasChanged('image')) {
            ImageProcessingService::deleteAllSizes(path: $author->getOriginal('image'), disk: 'authors');
        }

        if (($author->wasRecentlyCreated || $author->wasChanged('image')) && $author->image) {
            GenerateImageSizes::dispatch(Author::class, $author->id)->afterCommit();
        }
    }

    public function deleted(Author $author): void
    {
        ImageProcessingService::deleteAllSizes(path: $author->image, disk: 'authors');
    }
}
