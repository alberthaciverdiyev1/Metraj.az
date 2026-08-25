<?php

namespace App\Modules\Blog\Controllers;

use App\Modules\Blog\Services\BlogService;
use App\Modules\Blog\Models\Blog;
use App\Modules\Shared\Concerns\CachesGuestPage;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlogController extends Controller
{
    use CachesGuestPage;

    public function __construct(
        protected BlogService $blogService,
    ) {}

    public function index(\Illuminate\Http\Request $request): \Illuminate\Http\Response|View
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search');

        if (! $request->has('_cache_bust')) {
            return $this->cacheGuestPage($request, 'blog_list', 60, function () use ($category, $search) {
                return $this->renderIndex($category, $search);
            });
        }

        return response($this->renderIndex($category, $search));
    }

    protected function renderIndex(string $category, ?string $search): string
    {
        $blogs = $this->blogService->index(12, $category, $search);
        $categories = Blog::published()->whereNotNull('category')->distinct()->pluck('category');

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
        ];

        return view('pages.blog.list', compact('blogs', 'categories', 'category', 'search', 'breadcrumbs'))->render();
    }

    public function show(Blog $blog): View
    {
        $related = $this->blogService->related($blog, 3);

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
            ['label' => $blog->title],
        ];

        return view('pages.blog.show', compact('blog', 'related', 'breadcrumbs'));
    }
}
