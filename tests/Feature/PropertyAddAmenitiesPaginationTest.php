<?php

namespace Tests\Feature;

use App\Modules\Location\Models\Amenity;
use Tests\TestCase;

class PropertyAddAmenitiesPaginationTest extends TestCase
{
    public function test_amenities_api_endpoint_paginates_properly(): void
    {
        // Ensure we have at least 25 amenities for testing
        $count = Amenity::count();
        if ($count < 25) {
            for ($i = $count + 1; $i <= 25; $i++) {
                Amenity::create([
                    'name' => "Təchizat Test $i",
                    'is_active' => true,
                ]);
            }
        }

        $response = $this->getJson(route('add-property.amenities', ['page' => 1, 'per_page' => 20]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'has_more',
            'total',
        ]);
        $this->assertEquals(1, $response->json('current_page'));
        $this->assertTrue($response->json('has_more'));
        $this->assertCount(20, $response->json('data'));

        // Page 2
        $responsePage2 = $this->getJson(route('add-property.amenities', ['page' => 2, 'per_page' => 20]));
        $responsePage2->assertStatus(200);
        $this->assertEquals(2, $responsePage2->json('current_page'));
        $this->assertGreaterThanOrEqual(5, count($responsePage2->json('data')));
    }

    public function test_add_property_page_shows_load_more_button_when_more_than_20_amenities(): void
    {
        $response = $this->get(route('add-property'));
        $response->assertStatus(200);
        $response->assertSee('Təchizatlar və İmkanlar');
        if (Amenity::count() > 20) {
            $response->assertSee('load_more_amenities_btn');
            $response->assertSee('Daha çox göstər');
        }
    }
}
