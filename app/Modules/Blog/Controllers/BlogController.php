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

    public function index(): View
    {
        $blogs = $this->blogService->index(12);

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
        ];

        return view('blog::pages.blog.list', compact('blogs', 'breadcrumbs'));
    }

    public function show(Blog $blog): View
    {
        $related = $this->blogService->related($blog, 3);

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
            ['label' => $blog->title],
        ];

        return view('blog::pages.blog.show', compact('blog', 'related', 'breadcrumbs'));
    }
}
