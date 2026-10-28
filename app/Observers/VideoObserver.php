<?php

namespace App\Observers;

use App\Jobs\GenerateImageSizes;
use App\Models\Article;
use App\Models\Video;
use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Storage;

class VideoObserver
{
    public function saved(Video $video): void
    {
        if ($video->wasChanged('image')) {
            ImageProcessingService::deleteAllSizes(path: $video->getOriginal('image'), disk: 'videos');
        }

        if (($video->wasRecentlyCreated || $video->wasChanged('image')) && $video->image) {
            GenerateImageSizes::dispatch(Video::class, $video->id)->afterCommit();
        }
    }

    public function forceDeleted(Video $video): void
    {
        ImageProcessingService::deleteAllSizes(path: $video->image, disk: 'videos');
    }
}
