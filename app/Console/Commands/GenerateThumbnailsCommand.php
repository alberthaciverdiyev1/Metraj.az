<?php

namespace App\Console\Commands;

use App\Modules\Property\Models\PropertyImage;
use App\Modules\Shared\Services\ImageOptimizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateThumbnailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:generate-thumbnails {--force : Re-generate thumbnails even if already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate compact WebP thumbnails for all property images';

    public function handle(ImageOptimizerService $optimizer): int
    {
        $force = $this->option('force');
        $query = PropertyImage::query();

        if (!$force) {
            $query->whereNull('thumbnail_url');
        }

        $images = $query->get();
        $total = $images->count();

        if ($total === 0) {
            $this->info('No images need thumbnail generation.');
            return self::SUCCESS;
        }

        $this->info("Generating thumbnails for {$total} property images...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $generated = 0;
        $failed = 0;

        foreach ($images as $img) {
            $rawPath = $img->getRawOriginal('url');

            // Skip external urls
            if (empty($rawPath) || str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
                $bar->advance();
                continue;
            }

            $absoluteSource = Storage::disk('public')->path($rawPath);

            if (!file_exists($absoluteSource)) {
                $failed++;
                $bar->advance();
                continue;
            }

            $thumbRel = $optimizer->createThumbnail($absoluteSource, 'properties/thumbnails');

            if ($thumbRel) {
                $img->update(['thumbnail_url' => $thumbRel]);
                $generated++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done! Generated: {$generated}, Skipped/Failed: {$failed}");

        return self::SUCCESS;
    }
}
