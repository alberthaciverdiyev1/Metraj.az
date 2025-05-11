<?php

namespace Modules\Blog\Http\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'author_name' => $this->author_name,

            'category' => [
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],

            'tags' => $this->tags->map(function ($tag) {
                return [
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            }),
        ];
    }
}
