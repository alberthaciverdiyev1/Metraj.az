<?php

namespace App\Modules\Shared\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Şəkildən kiçik həcmli (5-25 KB), yüksək keyfiyyətli WebP thumbnail yaradır.
     *
     * @param  string|UploadedFile  $source  Faylın mütləq yolu və ya UploadedFile obyekti
     * @param  string  $directory  Storage daxilindəki qovluq (məs: 'properties/thumbnails')
     * @param  int  $maxWidth  Maksimal en (standart: 600px)
     * @param  int  $maxHeight  Maksimal hündürlük (standart: 450px)
     * @param  int  $quality  Sıxılma keyfiyyəti (1-100, standart: 75)
     * @return string|null  Yaradılmış thumbnail-ın storage nisbi yolu
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

            // GD ilə şəkli oxuyuruq
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

            // EXIF Orientation düzəlişi (mobil telefonla çəkilmiş şəkillərin çevrilməsi üçün)
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

            // Proporsional ölçü hesablama
            $ratio = min($maxWidth / max($origWidth, 1), $maxHeight / max($origHeight, 1));
            // Əgər orijinal şəkil artıq kiçikdirsə, böyütmürük
            if ($ratio > 1) {
                $ratio = 1;
            }

            $newWidth = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);

            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            // Şəffaflığı qorumaq (PNG/WebP üçün)
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

            // Storage qovluğunu yoxlayırıq
            Storage::disk('public')->makeDirectory($directory);

            $filename = 'thumb_' . Str::random(30) . '.webp';
            $relativeDestPath = trim($directory, '/') . '/' . $filename;
            $absoluteDestPath = Storage::disk('public')->path($relativeDestPath);

            // WebP formatında yüksək sıxılma ilə yazırıq (adətən 8-20 KB)
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
            \Illuminate\Support\Facades\Log::warning('Thumbnail creation failed: ' . $e->getMessage());
            return null;
        }
    }
}
