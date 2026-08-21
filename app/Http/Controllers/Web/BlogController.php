<?php

namespace App\Http\Controllers\Web;

use App\Core\Infrastructure\Persistence\Eloquent\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::published()->latest('published_at')->paginate(12);

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
        ];

        return view('pages.blog.list', compact('blogs', 'breadcrumbs'));
    }

    public function show(Blog $blog): View
    {
        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $breadcrumbs = [
            ['label' => __('Home'), 'url' => '/'],
            ['label' => __('Blog'), 'url' => '/blog'],
            ['label' => $blog->title],
        ];

        return view('pages.blog.show', compact('blog', 'related', 'breadcrumbs'));
    }
}
