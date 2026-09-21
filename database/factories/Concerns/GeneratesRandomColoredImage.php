<?php

namespace Database\Factories\Concerns;

trait GeneratesRandomColoredImage
{
    private function generateRandomColoredImage(int $width, int $height, ?string $text, string $storageDir, string $filenamePrefix): string
    {
        $image = imagecreatetruecolor($width, $height);

        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        $bgColor = imagecolorallocate($image, $r, $g, $b);
        imagefill($image, 0, 0, $bgColor);

        if ($text) {
            $text = strtoupper($text);
            $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
            $textColor = $brightness > 128
                ? imagecolorallocate($image, 0, 0, 0)
                : imagecolorallocate($image, 255, 255, 255);

            $font = 5;
            $textWidth = imagefontwidth($font) * strlen($text);
            $textHeight = imagefontheight($font);
            $x = (int)(($width - $textWidth) / 2);
            $y = (int)(($height - $textHeight) / 2);
            imagestring($image, $font, $x, $y, $text, $textColor);
        }

        $filename = $filenamePrefix . '_' . uniqid() . '.png';
        $path = $storageDir . '/' . $filename;

        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        imagepng($image, $path);
        unset($image);

        return $filename;
    }
}
