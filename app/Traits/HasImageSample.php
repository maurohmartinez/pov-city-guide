<?php

namespace App\Traits;

use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait HasImageSample
{
    public function addSampleImage(string $disk): void
    {
        foreach (array_keys(ImageProcessingService::MAX_WIDTHS) as $size) {
            $this->addImageSampleSizeIfNeeded($disk, $size);
        }
    }

    private function addImageSampleSizeIfNeeded(string $disk, string $size): void
    {
        if (!Storage::disk($disk)->exists($size . '/sample.jpg.jpg') || !Storage::disk($disk)->exists($size . '/sample.jpg')) {
            Storage::disk($disk)->makeDirectory($size);
        }

        if (!Storage::disk($disk)->exists($size . '/sample.jpg.jpg')) {
            File::copy(public_path('images/sample.jpg'), Storage::disk($disk)->path($size . '/sample.jpg.jpg'));
        }

        if (!Storage::disk($disk)->exists('/sample.jpg')) {
            File::copy(public_path('images/sample.jpg'), Storage::disk($disk)->path('/sample.jpg'));
        }
    }
}
