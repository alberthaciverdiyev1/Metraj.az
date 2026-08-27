<?php

namespace App\Services;

use App\Modules\Property\Models\Property;
use App\Modules\Blog\Models\Blog;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Roommate\Models\RoommateListing;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use Illuminate\Support\Facades\File;

class SitemapService
{
    public const CHUNK_SIZE = 10000; // Auto-split limit per sitemap file

    public function generate(): array
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $urls = [];

        // 1. Static pages
        $staticPaths = [
            '/',
            '/listing',
            '/blog',
            '/agencies',
            '/about-us',
            '/contact',
            '/faq',
            '/user-agreement',
            '/privacy-policy',
            '/terms-of-use',
            '/otaq-yoldasi',
            '/axtariram',
        ];

        foreach ($staticPaths as $path) {
            $urls[] = [
                'loc' => $baseUrl . $path,
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
        }

        // 2. Properties (Published)
        Property::where('status', 'published')->orderBy('id', 'desc')->chunk(500, function ($properties) use ($baseUrl, &$urls) {
            foreach ($properties as $property) {
                $urls[] = [
                    'loc' => $baseUrl . '/elan/' . $property->slug,
                    'lastmod' => ($property->updated_at ?? $property->created_at ?? now())->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        });

        // 3. Blog articles (Published)
        if (class_exists(Blog::class)) {
            Blog::published()->orderBy('id', 'desc')->chunk(100, function ($blogs) use ($baseUrl, &$urls) {
                foreach ($blogs as $blog) {
                    $urls[] = [
                        'loc' => $baseUrl . '/blog/' . $blog->slug,
                        'lastmod' => ($blog->updated_at ?? $blog->published_at ?? now())->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                }
            });
        }

        // 4. Agencies
        if (class_exists(Agency::class)) {
            Agency::orderBy('id', 'desc')->chunk(100, function ($agencies) use ($baseUrl, &$urls) {
                foreach ($agencies as $agency) {
                    $urls[] = [
                        'loc' => $baseUrl . '/agentlik/' . ($agency->slug ?? $agency->id),
                        'lastmod' => ($agency->updated_at ?? $agency->created_at ?? now())->toIso8601String(),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                }
            });
        }

        // 5. Agents
        if (class_exists(Agent::class)) {
            Agent::where('is_active', true)->orderBy('id', 'desc')->chunk(100, function ($agents) use ($baseUrl, &$urls) {
                foreach ($agents as $agent) {
                    $urls[] = [
                        'loc' => $baseUrl . '/agent/' . $agent->id,
                        'lastmod' => ($agent->updated_at ?? $agent->created_at ?? now())->toIso8601String(),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                }
            });
        }

        // 6. Roommate Listings (Published)
        if (class_exists(RoommateListing::class)) {
            RoommateListing::where('status', 'published')->orderBy('id', 'desc')->chunk(100, function ($listings) use ($baseUrl, &$urls) {
                foreach ($listings as $listing) {
                    $urls[] = [
                        'loc' => $baseUrl . '/otaq-yoldasi/' . $listing->slug,
                        'lastmod' => ($listing->updated_at ?? $listing->created_at ?? now())->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            });
        }

        // 7. Property Requests (Published)
        if (class_exists(PropertyRequest::class)) {
            PropertyRequest::where('status', 'published')->orderBy('id', 'desc')->chunk(100, function ($requests) use ($baseUrl, &$urls) {
                foreach ($requests as $request) {
                    $urls[] = [
                        'loc' => $baseUrl . '/axtariram/' . $request->slug,
                        'lastmod' => ($request->updated_at ?? $request->created_at ?? now())->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            });
        }

        $totalUrls = count($urls);
        $generatedFiles = [];
        $publicPath = public_path();

        // Clean up old sitemap files
        $oldFiles = File::glob($publicPath . '/sitemap*.xml');
        foreach ($oldFiles as $oldFile) {
            @unlink($oldFile);
        }

        if ($totalUrls <= self::CHUNK_SIZE) {
            // Single sitemap file
            $filePath = $publicPath . '/sitemap.xml';
            $this->writeSitemapFile($filePath, $urls);
            $generatedFiles[] = [
                'name' => 'sitemap.xml',
                'url' => $baseUrl . '/sitemap.xml',
                'count' => $totalUrls,
            ];
        } else {
            // Split into chunks
            $chunks = array_chunk($urls, self::CHUNK_SIZE);
            $sitemaps = [];

            foreach ($chunks as $index => $chunkUrls) {
                $pageNum = $index + 1;
                $fileName = "sitemap_{$pageNum}.xml";
                $filePath = $publicPath . '/' . $fileName;
                $this->writeSitemapFile($filePath, $chunkUrls);

                $sitemaps[] = [
                    'loc' => $baseUrl . '/' . $fileName,
                    'lastmod' => now()->toIso8601String(),
                ];

                $generatedFiles[] = [
                    'name' => $fileName,
                    'url' => $baseUrl . '/' . $fileName,
                    'count' => count($chunkUrls),
                ];
            }

            // Write index sitemap
            $indexPath = $publicPath . '/sitemap.xml';
            $this->writeSitemapIndexFile($indexPath, $sitemaps);

            array_unshift($generatedFiles, [
                'name' => 'sitemap.xml (Sitemap İndeksi)',
                'url' => $baseUrl . '/sitemap.xml',
                'count' => $totalUrls,
            ]);
        }

        return $generatedFiles;
    }

    private function writeSitemapFile(string $path, array $urls): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        File::put($path, $xml);
    }

    private function writeSitemapIndexFile(string $path, array $sitemaps): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($sitemaps as $sitemap) {
            $xml .= '    <sitemap>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($sitemap['loc']) . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $sitemap['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        File::put($path, $xml);
    }
}
