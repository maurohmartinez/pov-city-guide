<?php

namespace App\Observers;

use App\Jobs\GenerateImageSizes;
use App\Models\Category;
use App\Services\ImageProcessingService;

class CategoryObserver
{
    public function saved(Category $category): void
    {
        if ($category->wasChanged('image')) {
            ImageProcessingService::deleteAllSizes(path: $category->getOriginal('image'), disk: 'categories');
        }

        if (($category->wasRecentlyCreated || $category->wasChanged('image')) && $category->image) {
            GenerateImageSizes::dispatch(Category::class, $category->id)->afterCommit();
        }
    }

    public function deleted(Category $category): void
    {
        ImageProcessingService::deleteAllSizes(path: $category->image, disk: 'categories');
    }
}
