<?php

namespace Modules\Blog\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Blog\Http\Entities\Blog;
use Modules\Blog\Http\Requests\BlogStoreRequest;
use Modules\Blog\Http\Requests\BlogUpdateRequest;
use Modules\Blog\Http\Transformers\BlogDetailsResource;
use Modules\Blog\Http\Transformers\BlogListResource;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BlogController extends Controller
{

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view blogs')->only('index');
//            $this->middleware('permission:create blog')->only('create');
//            $this->middleware('permission:store blog')->only('store');
//            $this->middleware('permission:edit blog')->only('edit');
//            $this->middleware('permission:update blog')->only('update');
//            $this->middleware('permission:destroy blog')->only('destroy');
//        }
    }


    /**
     * Display a listing of the resource.
     */
    public function blogs(): AnonymousResourceCollection
    {
//        $blogs = Blog::with(['category'])->latest()->get();
        $blogs = Blog::query()
            ->select('id', 'name', 'slug', 'created_at', 'category_id')
            ->with([
                'category' => function ($query) {
                    $query->select('id', 'name', 'slug');
                }
            ])
            ->latest()
            ->get();


        return BlogListResource::collection($blogs);
    }

    public function blog(string $slug)
    {
        $blog = Blog::query()
            ->select(['id', 'name', 'slug', 'description', 'author_name', 'category_id'])
            ->with([
                'category:id,name,slug',
                'tags:id,name,slug'
            ])
            ->where('slug', $slug)
            ->first();

        if (!$blog) {
            return response()->json([
                'success' => 400,
                'message' => __('Blog not found!')
            ]);
        }

        return BlogDetailsResource::make($blog);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request): JsonResponse
    {

        try {
            $validatedData = $request->safe()->toArray();

            Blog::query()->create($validatedData);

            return response()->json([
                'message' => 'Blog created successfully.',
                'status_code' => 201
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(string $slug ,BlogUpdateRequest $request)
    {
        try {
            $validatedData = $request->safe()->toArray();
            $validatedData['slug'] = Str::slug($validatedData['name']) .'-'.rand(10,1000000);

            $updated = Blog::query()->where('slug', $slug)->update($validatedData);
            if ($updated) {
                return response()->json([
                    'message' => 'Blog updated successfully.',
                    'status_code' => 200
                ]);
            } else {
                return response()->json([
                    'message' => 'Blog not found or no changes detected.',
                    'status_code' => 404
                ]);
            }

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            Blog::query()->where('slug', $slug)->delete();
            return response()->json([
                'message' => 'Blog deleted successfully.',
                'status_code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
