<?php

namespace App\Jobs;

use App\Services\ImageProcessingService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateImageSizes implements ShouldQueue
{
    use Queueable, Dispatchable, Batchable;

    public function __construct(public string $className, public int $id)
    {
    }

    public function handle(): void
    {
        ImageProcessingService::processAllSizes($this->className::findOrFail($this->id));
    }
}
