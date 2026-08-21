<?php

namespace App\Modules\Blog\Repositories;

use App\Modules\Blog\Contracts\BlogRepositoryInterface;
use App\Modules\Blog\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(
        protected Blog $model,
    ) {
    }

    public function paginatePublished(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->published()->latest('published_at')->paginate($perPage);
    }

    public function related(Blog $blog, int $limit = 3): Collection
    {
        return $this->model->published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
