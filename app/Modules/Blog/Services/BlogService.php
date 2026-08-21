<?php

namespace App\Modules\Blog\Services;

use App\Modules\Blog\Repositories\BlogRepositoryInterface;
use App\Modules\Blog\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Bloq siyahısı və detalları ilə bağlı iş məntiqi.
 */
class BlogService
{
    public function __construct(
        protected BlogRepositoryInterface $blogRepository,
    ) {}

    public function index(int $perPage = 12): LengthAwarePaginator
    {
        return $this->blogRepository->paginatePublished($perPage);
    }

    /**
     * @return Collection<int, Blog>
     */
    public function related(Blog $blog, int $limit = 3): Collection
    {
        return $this->blogRepository->related($blog, $limit);
    }
}
