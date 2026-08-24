<?php

namespace Tests\Feature;

use App\Modules\Shared\Models\User;
use Tests\TestCase;

class NavbarFavoritesCompareAuthTest extends TestCase
{
    public function test_favorites_and_compare_are_visible_in_navbar(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="favorites-count"', false);
        $response->assertSee('id="compares-count"', false);
        $response->assertSee('href="/favorites"', false);
        $response->assertSee('href="/compares"', false);
    }

    public function test_authenticated_user_sees_favorites_and_compare_in_navbar(): void
    {
        $user = new User([
            'name' => 'Nav User Test',
            'email' => 'nav_user@metraj.az',
        ]);
        $user->id = 9999;

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="favorites-count"', false);
        $response->assertSee('id="compares-count"', false);
        $response->assertSee('href="/favorites"', false);
        $response->assertSee('href="/compares"', false);
    }

    public function test_deal_type_links_are_present_in_navbar(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/listing?deal_type=sale"', false);
        $response->assertSee('href="/listing?deal_type=rent_monthly"', false);
        $response->assertSee('href="/listing?deal_type=rent_daily"', false);
        $response->assertSee('href="/axtariram"', false);
        $response->assertSee('Alqı-satqı');
        $response->assertSee('Kirayə');
        $response->assertSee('Günlük');
        $response->assertSee('Axtarıram');
    }
}
