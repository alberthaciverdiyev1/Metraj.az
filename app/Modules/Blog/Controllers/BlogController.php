<?php

namespace App\Modules\Blog\Controllers;

use App\Modules\Blog\Services\BlogService;
use App\Modules\Blog\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected BlogService $blogService,
    ) {}

    public function index(\Illuminate\Http\Request $request): View
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search');

        $blogs = $this->blogService->index(12, $category, $search);
        $categories = Blog::published()->whereNotNull('category')->distinct()->pluck('category');

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
        ];

        return view('pages.blog.list', compact('blogs', 'categories', 'category', 'search', 'breadcrumbs'));
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
