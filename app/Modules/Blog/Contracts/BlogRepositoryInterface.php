<?php

namespace App\Modules\Blog\Contracts;

use App\Modules\Blog\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BlogRepositoryInterface
{
    public function paginatePublished(int $perPage = 12): LengthAwarePaginator;

    /**
     * Eyni kateqoriyadan oxşar bloqlar (bu bloq xaric).
     *
     * @return Collection<int, Blog>
     */
    public function related(Blog $blog, int $limit = 3): Collection;
}
