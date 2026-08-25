<?php

namespace Tests\Feature;

use App\Modules\Property\Models\Compare;
use App\Modules\Property\Models\Favorite;
use App\Modules\Property\Models\Property;
use App\Modules\Shared\Models\User;
use Tests\TestCase;

class BackendFavoritesAndComparesTest extends TestCase
{
    public function test_can_toggle_favorite_via_backend_api_as_guest(): void
    {
        $this->withoutMiddleware();

        $property = Property::first();
        if (! $property) {
            $this->markTestSkipped('No property found');
        }

        // Add
        $response = $this->postJson('/api/favorites/toggle', ['property_id' => $property->id]);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_favorite' => true,
        ]);

        $this->assertDatabaseHas('favorites', [
            'property_id' => $property->id,
        ]);

        // Remove
        $response2 = $this->postJson('/api/favorites/toggle', ['property_id' => $property->id]);
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_favorite' => false,
        ]);
    }

    public function test_can_toggle_compare_via_backend_api_and_enforces_limit(): void
    {
        $this->withoutMiddleware();

        $properties = Property::take(5)->get();
        if ($properties->count() < 5) {
            $this->markTestSkipped('Need at least 5 properties');
        }

        $user = User::first();
        if (! $user) {
            $user = User::create([
                'name' => 'Comp User',
                'email' => 'comp_user_' . time() . '@kibriskare.com',
                'password' => bcrypt('password123'),
            ]);
        }
        Compare::where('user_id', $user->id)->delete();

        // Add 4 properties
        for ($i = 0; $i < 4; $i++) {
            $res = $this->actingAs($user)->postJson('/api/compares/toggle', ['property_id' => $properties[$i]->id]);
            $res->assertStatus(200);
            $res->assertJson(['success' => true, 'is_compared' => true]);
        }

        // 5th should fail with limit error
        $res5 = $this->actingAs($user)->postJson('/api/compares/toggle', ['property_id' => $properties[4]->id]);
        $res5->assertStatus(422);
        $res5->assertJson(['success' => false, 'limit_reached' => true]);
    }

    public function test_compares_page_renders_with_backend_data(): void
    {
        $response = $this->get('/compares');

        $response->assertStatus(200);
        $response->assertSee('Mülkləri Müqayisə Et');
    }

    public function test_compares_page_renders_table_with_items(): void
    {
        $user = User::first();
        $property = Property::first();
        if ($user && $property) {
            Compare::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);

            $response = $this->actingAs($user)->get('/compares');
            $response->assertStatus(200);
            $response->assertSee($property->title);
            $response->assertSee('id="compareTableContainer"', false);
        }
    }
}
