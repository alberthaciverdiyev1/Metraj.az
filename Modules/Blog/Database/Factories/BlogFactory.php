<?php

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use \Modules\Blog\Http\Entities\Blog;
use Modules\Blog\Http\Entities\BlogCategory;
use Modules\Blog\Http\Entities\Tag;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'author_name' => $this->faker->userName(),
            'description' => $this->faker->text(),
            'category_id' => BlogCategory::inRandomOrder()->first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Blog $blog) {
            $keywords = Tag::inRandomOrder()->take(2)->get();

            if ($keywords->count() == 0) {
                $keywords = Tag::factory()->count(3)->create();
            }
            $blog->tags()->attach($keywords->pluck('id'));
        });
    }
}
