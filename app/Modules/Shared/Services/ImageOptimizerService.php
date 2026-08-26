<?php

namespace App\Modules\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    public const WATERMARK_TEXT = 'KibrisKare.com';

    /**
     * Şəkli watermark (KibrisKare.com) ilə yaddaşa yazır.
     *
     * @param  string|UploadedFile  $source
     * @param  string  $directory  Storage qovluğu (məs: 'properties')
     * @param  string  $text  Watermark mətni
     * @return string  Yaddaşdakı nisbi yol (məs: 'properties/abc123xyz.jpg')
     */
    public function saveWithWatermark(
        string|UploadedFile $source,
        string $directory = 'properties',
        string $text = self::WATERMARK_TEXT
    ): string {
        try {
            $sourcePath = $source instanceof UploadedFile ? $source->getRealPath() : $source;
            $extension = $source instanceof UploadedFile ? $source->getClientOriginalExtension() : pathinfo($sourcePath, PATHINFO_EXTENSION);
            $extension = strtolower($extension ?: 'jpg');

            if (!file_exists($sourcePath)) {
                if ($source instanceof UploadedFile) {
                    return $source->store($directory, 'public');
                }
                return $source;
            }

            $imageInfo = @getimagesize($sourcePath);
            if (!$imageInfo) {
                if ($source instanceof UploadedFile) {
                    return $source->store($directory, 'public');
                }
                return $source;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            $srcImage = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
                IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
                default => null,
            };

            if (!$srcImage) {
                if ($source instanceof UploadedFile) {
                    return $source->store($directory, 'public');
                }
                return $source;
            }

            // EXIF Orientation düzəlişi
            if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($sourcePath);
                if (!empty($exif['Orientation'])) {
                    $srcImage = match ($exif['Orientation']) {
                        3 => imagerotate($srcImage, 180, 0),
                        6 => imagerotate($srcImage, -90, 0),
                        8 => imagerotate($srcImage, 90, 0),
                        default => $srcImage,
                    };
                }
            }

            // Watermark əlavə edirik
            $this->addWatermarkToGdImage($srcImage, $text);

            Storage::disk('public')->makeDirectory($directory);

            $filename = Str::random(40) . '.' . ($extension === 'png' ? 'png' : 'jpg');
            $relativeDestPath = trim($directory, '/') . '/' . $filename;
            $absoluteDestPath = Storage::disk('public')->path($relativeDestPath);

            if ($extension === 'png') {
                imagepng($srcImage, $absoluteDestPath, 8);
            } else {
                imagejpeg($srcImage, $absoluteDestPath, 90);
            }

            imagedestroy($srcImage);

            return $relativeDestPath;
        } catch (\Throwable $e) {
            Log::warning('Watermark failed, falling back to standard store: ' . $e->getMessage());
            if ($source instanceof UploadedFile) {
                return $source->store($directory, 'public');
            }
            return $source;
        }
    }

    /**
     * Mövcud faylın üzərinə in-place watermark (KibrisKare.com) tətbiq edir.
     */
    public function addWatermarkToFile(string $filePath, string $text = self::WATERMARK_TEXT): bool
    {
        try {
            if (!file_exists($filePath)) {
                return false;
            }

            $imageInfo = @getimagesize($filePath);
            if (!$imageInfo) {
                return false;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            $srcImage = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($filePath),
                IMAGETYPE_PNG => @imagecreatefrompng($filePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : null,
                IMAGETYPE_GIF => @imagecreatefromgif($filePath),
                default => null,
            };

            if (!$srcImage) {
                return false;
            }

            if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    $srcImage = match ($exif['Orientation']) {
                        3 => imagerotate($srcImage, 180, 0),
                        6 => imagerotate($srcImage, -90, 0),
                        8 => imagerotate($srcImage, 90, 0),
                        default => $srcImage,
                    };
                }
            }

            $this->addWatermarkToGdImage($srcImage, $text);

            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($ext === 'png' || $imageType === IMAGETYPE_PNG) {
                imagepng($srcImage, $filePath, 8);
            } elseif ($ext === 'webp' || $imageType === IMAGETYPE_WEBP) {
                if (function_exists('imagewebp')) {
                    imagewebp($srcImage, $filePath, 85);
                } else {
                    imagejpeg($srcImage, $filePath, 90);
                }
            } else {
                imagejpeg($srcImage, $filePath, 90);
            }

            imagedestroy($srcImage);
            return true;
        } catch (\Throwable $e) {
            Log::warning('In-place watermark failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Şəkildən kiçik həcmli (5-25 KB), yüksək keyfiyyətli WebP thumbnail yaradır.
     */
    public function createThumbnail(
        string|UploadedFile $source,
        string $directory = 'properties/thumbnails',
        int $maxWidth = 600,
        int $maxHeight = 450,
        int $quality = 75
    ): ?string {
        try {
            $sourcePath = $source instanceof UploadedFile ? $source->getRealPath() : $source;

            if (!file_exists($sourcePath)) {
                return null;
            }

            $imageInfo = @getimagesize($sourcePath);
            if (!$imageInfo) {
                return null;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            $srcImage = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
                IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
                default => null,
            };

            if (!$srcImage) {
                return null;
            }

            if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($sourcePath);
                if (!empty($exif['Orientation'])) {
                    $srcImage = match ($exif['Orientation']) {
                        3 => imagerotate($srcImage, 180, 0),
                        6 => imagerotate($srcImage, -90, 0),
                        8 => imagerotate($srcImage, 90, 0),
                        default => $srcImage,
                    };
                    $origWidth = imagesx($srcImage);
                    $origHeight = imagesy($srcImage);
                }
            }

            $ratio = min($maxWidth / max($origWidth, 1), $maxHeight / max($origHeight, 1));
            if ($ratio > 1) {
                $ratio = 1;
            }

            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);

            imagecopyresampled(
                $thumb,
                $srcImage,
                0, 0, 0, 0,
                $newWidth,
                $newHeight,
                $origWidth,
                $origHeight
            );

            Storage::disk('public')->makeDirectory($directory);

            $filename = 'thumb_' . Str::random(30) . '.webp';
            $relativeDestPath = trim($directory, '/') . '/' . $filename;
            $absoluteDestPath = Storage::disk('public')->path($relativeDestPath);

            if (function_exists('imagewebp')) {
                imagewebp($thumb, $absoluteDestPath, $quality);
            } else {
                $filename = 'thumb_' . Str::random(30) . '.jpg';
                $relativeDestPath = trim($directory, '/') . '/' . $filename;
                $absoluteDestPath = Storage::disk('public')->path($relativeDestPath);
                imagejpeg($thumb, $absoluteDestPath, $quality);
            }

            imagedestroy($srcImage);
            imagedestroy($thumb);

            return $relativeDestPath;
        } catch (\Throwable $e) {
            Log::warning('Thumbnail creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * GD şəklinin sağ aşağı küncünə müasir, oxunaqlı Watermark nişanı əlavə edir.
     */
    public function addWatermarkToGdImage(&$image, string $text = self::WATERMARK_TEXT): void
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Şəkil ölçüsünə görə dinamik proporsional şrift ölçüsü
        $fontSize = max(13, (int) round($width / 42));
        $fontFile = $this->getFontPath();

        if ($fontFile && function_exists('imagettfbbox') && function_exists('imagettftext')) {
            $bbox = @imagettfbbox($fontSize, 0, $fontFile, $text);
            if ($bbox) {
                $textWidth = abs($bbox[4] - $bbox[0]);
                $textHeight = abs($bbox[5] - $bbox[1]);

                $padX = (int) round($fontSize * 0.7);
                $padY = (int) round($fontSize * 0.45);

                $boxWidth = $textWidth + ($padX * 2);
                $boxHeight = $textHeight + ($padY * 2);

                $margin = (int) round(max(12, $width * 0.02));
                $boxX = $width - $boxWidth - $margin;
                $boxY = $height - $boxHeight - $margin;

                // Tünd yarım-şəffaf arxa fon və aydın ağ mətn
                $badgeBg = imagecolorallocatealpha($image, 15, 23, 42, 50); // ~60% qaranlıq
                $textColor = imagecolorallocatealpha($image, 255, 255, 255, 0); // Tam ağ
                $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 75); // Mətn kölgəsi

                // Arxa plan nişanı
                imagefilledrectangle($image, $boxX, $boxY, $boxX + $boxWidth, $boxY + $boxHeight, $badgeBg);

                // Mətn kölgəsi və əsas mətn
                $textX = $boxX + $padX;
                $textY = $boxY + $padY + $textHeight;

                @imagettftext($image, $fontSize, 0, $textX + 1, $textY + 1, $shadowColor, $fontFile, $text);
                @imagettftext($image, $fontSize, 0, $textX, $textY, $textColor, $fontFile, $text);
                return;
            }
        }

        // TTF mümkün olmadıqda standart GD şriftindən istifadə
        $font = 5;
        $fontWidth = imagefontwidth($font);
        $fontHeight = imagefontheight($font);
        $textWidth = $fontWidth * strlen($text);
        $textHeight = $fontHeight;

        $padX = 10;
        $padY = 6;
        $margin = 15;

        $boxWidth = $textWidth + ($padX * 2);
        $boxHeight = $textHeight + ($padY * 2);

        $boxX = $width - $boxWidth - $margin;
        $boxY = $height - $boxHeight - $margin;

        $badgeBg = imagecolorallocatealpha($image, 15, 23, 42, 50);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image, $boxX, $boxY, $boxX + $boxWidth, $boxY + $boxHeight, $badgeBg);
        imagestring($image, $font, $boxX + $padX, $boxY + $padY, $text, $textColor);
    }

    /**
     * Watermark üçün istifadə ediləcək TTF şrift faylını tapır.
     */
    protected function getFontPath(): ?string
    {
        $bundled = resource_path('fonts/watermark-font.ttf');
        if (file_exists($bundled)) {
            return $bundled;
        }

        $candidates = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/noto/NotoSansMono-Bold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
