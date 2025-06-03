<?php

namespace Modules\WebUI\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class BlogController extends Controller
{
    public function blog()
    {
        $css = ['blog.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css'];
        $js = ['blog.js', 'gotop.js'];
        $cities = [];

        $response = Http::get(config('app.api_url') . '/api/blog')->json();
        $blog = $response['data'] ?? [];
        return view('webui::Pages.blog', compact('css', 'js', 'cities', 'blog'));
    }

    public function blogDetail($slug)
    {
        $css = ['blog-detail.css', 'app.css', 'components.css', 'listing-details.css', 'agencies.css', 'blog.css'];
        $js = ['blog-detail.js', 'gotop.js'];

        $response = Http::get(config('app.api_url') . "/api/blog/{$slug}")->json();
        $blog = $response['data'] ?? abort(404);;

        $relatedPosts = array_filter($this->blog, function ($key) use ($slug) {
            return $key != $slug;
        }, ARRAY_FILTER_USE_KEY);

        $relatedPosts = array_slice($relatedPosts, 0, 3);

        return view('webui::Pages.blog-detail', compact('css', 'js', 'blog', 'relatedPosts'));
    }

}
