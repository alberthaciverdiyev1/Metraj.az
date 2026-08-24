<?php

namespace Tests\Feature;

use App\Modules\Property\Models\Property;
use Tests\TestCase;

class FavoritesPageTest extends TestCase
{
    public function test_favorites_page_renders_successfully(): void
    {
        $response = $this->get(route('favorites'));

        $response->assertStatus(200);
        $response->assertSee('Seçilmiş Elanlar');
        $response->assertSee('id="favoritesContainer"', false);
    }

    public function test_favorites_items_endpoint_returns_rendered_cards(): void
    {
        $this->withoutMiddleware();

        $property = Property::where('status', \App\Modules\Property\Enums\PropertyStatus::Published)->first();
        if (! $property) {
            $this->markTestSkipped('No published property found.');
        }

        $response = $this->postJson(route('favorites.items'), [
            'ids' => [$property->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 1,
        ]);
        $data = $response->json();
        $this->assertStringContainsString('data-fav-id="' . $property->id . '"', $data['html']);
    }

    public function test_favorites_items_endpoint_handles_empty_ids(): void
    {
        $this->withoutMiddleware();

        $response = $this->postJson(route('favorites.items'), [
            'ids' => [],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'count' => 0,
            'html' => '',
        ]);
    }
}
