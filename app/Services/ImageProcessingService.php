<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Laravel\Facades\Image;

class ImageProcessingService
{
    const string SIZE_LARGE = 'large';

    const string SIZE_MEDIUM = 'medium';

    const string SIZE_SMALL = 'small';

    const int JPEG_QUALITY = 85;

    const array MAX_WIDTHS = [
        self::SIZE_LARGE => 2400,
        self::SIZE_MEDIUM => 1024,
        self::SIZE_SMALL => 550,
    ];

    public static function processAllSizes(Category|Article $model): void
    {
        $disk = $model instanceof Category ? 'categories' : 'articles';

        self::processBySize($disk, self::SIZE_LARGE, $model);
        self::processBySize($disk, self::SIZE_MEDIUM, $model);
        self::processBySize($disk, self::SIZE_SMALL, $model);

        Storage::disk($disk)->move($model->image, $model->image);
    }

    public static function processBySize(string $disk, string $size, Category|Article $model): void
    {
        Storage::disk($disk)->makeDirectory($size);

        Image::decode(Storage::disk($disk)->path($model->image))
            ->scaleDown(width: self::MAX_WIDTHS[$size])
            ->encode(new JpegEncoder(quality: self::JPEG_QUALITY))
            ->save(Storage::disk($disk)->path($size . '/' . $model->image . '.jpg'));
    }

    public static function deleteAllSizes(string $path, string $disk): void
    {
        Storage::disk($disk)->delete(self::SIZE_LARGE . '/' . $path . '.jpg');
        Storage::disk($disk)->delete(self::SIZE_MEDIUM . '/' . $path . '.jpg');
        Storage::disk($disk)->delete(self::SIZE_SMALL . '/' . $path . '.jpg');
        Storage::disk($disk)->delete($path . '.jpg');
    }

    public static function getSmallPath(Category|Article $model): string
    {
        return Storage::disk($model instanceof Category ? 'categories' : 'articles')->url('small/' . $model->image . '.jpg');
    }
}
