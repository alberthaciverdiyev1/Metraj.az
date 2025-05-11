<?php

namespace Modules\Blog\Http\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=>$this->getAttribute('name'),
            'slug'=>$this->getAttribute('slug'),
            'date' => \Carbon\Carbon::parse($this->getAttribute('date'))->format('d F, Y'),
            'category'=>[
                'slug'=>$this->category->slug,
                'name'=>$this->category->name

            ],
//            'image'=>$this->image()
        ];
    }
}
