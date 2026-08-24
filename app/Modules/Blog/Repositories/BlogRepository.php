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

    public function paginatePublished(int $perPage = 12, ?string $category = null, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->published()->latest('published_at');

        if (!empty($category) && $category !== 'all') {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'ilike', "%{$term}%")
                    ->orWhere('excerpt', 'ilike', "%{$term}%")
                    ->orWhere('content', 'ilike', "%{$term}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
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
