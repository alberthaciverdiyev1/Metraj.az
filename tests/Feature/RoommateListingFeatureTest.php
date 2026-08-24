<?php

namespace Tests\Feature;

use App\Modules\Location\Models\City;
use App\Modules\Roommate\Enums\GenderPreference;
use App\Modules\Roommate\Enums\RoommateListingType;
use App\Modules\Roommate\Models\RoommateListing;
use App\Modules\Shared\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoommateListingFeatureTest extends TestCase
{
    public function test_roommates_catalog_page_renders_successfully(): void
    {
        $response = $this->get('/otaq-yoldasi');

        $response->assertStatus(200);
        $response->assertSee('Otaq & Ev Yoldaşı Axtarışı');
        $response->assertSee('Otaq Yoldaşı Elanı Ver');
    }

    public function test_roommate_create_page_renders_successfully(): void
    {
        $response = $this->get('/otaq-yoldasi/elan-ver');

        $response->assertStatus(200);
        $response->assertSee('Otaq / Ev Yoldaşı Elanı Yerləşdir');
        $response->assertSee('Evim var, otaq yoldaşı axtarıram');
        $response->assertSee('Ev axtarıram, ortaq yoldaş axtarıram');
    }

    public function test_can_submit_new_roommate_listing(): void
    {
        $this->withoutMiddleware();
        Storage::fake('public');

        $city = City::first();
        if (! $city) {
            $city = City::create([
                'name' => ['az' => 'Bakı'],
                'slug' => 'baki',
                'is_active' => true,
            ]);
        }

        $postData = [
            'listing_type' => 'have_room',
            'title' => 'Nərimanovda 2 otaqlı mənzilə tələbə qız axtarılır',
            'description' => 'Mənzil əla təmirlidir, bütün məişət texnikası mövcuddur. Təmizkar və sakit otaq yoldaşı axtarılır.',
            'price' => 170,
            'bills_included' => 1,
            'city_id' => $city->id,
            'location_note' => 'Nərimanov m/s yaxınlığı',
            'gender_preference' => 'female',
            'occupation_preference' => 'student',
            'smoker_allowed' => 0,
            'pet_allowed' => 0,
            'stay_duration' => 'Uzunmüddətli',
            'amenities' => ['Wi-Fi İnternet', 'Kondisioner', 'Paltaryuyan'],
            'contact_name' => 'Aysel Məmmədova',
            'contact_phone' => '+994501234567',
            'contact_whatsapp' => '+994501234567',
            'images' => [
                UploadedFile::fake()->create('room1.jpg', 100, 'image/jpeg'),
            ],
        ];

        $response = $this->post('/otaq-yoldasi/elan-ver', $postData);

        $this->assertDatabaseHas('roommate_listings', [
            'title' => 'Nərimanovda 2 otaqlı mənzilə tələbə qız axtarılır',
            'listing_type' => 'have_room',
            'gender_preference' => 'female',
            'price' => 170,
        ]);

        $listing = RoommateListing::where('title', 'Nərimanovda 2 otaqlı mənzilə tələbə qız axtarılır')->latest('id')->first();
        $this->assertNotNull($listing);
        $this->assertEquals(1, $listing->images()->count());

        $response->assertRedirect(route('roommates.show', $listing->slug));
    }

    public function test_roommate_details_page_renders_and_increments_views(): void
    {
        $city = City::first();
        $listing = RoommateListing::create([
            'listing_type' => RoommateListingType::HaveRoom,
            'title' => 'Test Otaq Yoldaşı Elanı ' . time(),
            'description' => 'Test otaq haqqında ətraflı məlumat mətni burada yerləşir.',
            'price' => 200,
            'city_id' => $city?->id,
            'gender_preference' => GenderPreference::Any,
            'contact_name' => 'Rəşad Əliyev',
            'contact_phone' => '+994551234567',
        ]);

        $initialViews = $listing->views_count;

        $response = $this->get('/otaq-yoldasi/' . $listing->slug);

        $response->assertStatus(200);
        $response->assertSee($listing->title);
        $response->assertSee('200 ₼');
        $response->assertSee('Rəşad Əliyev');

        $this->assertEquals($initialViews + 1, $listing->fresh()->views_count);
    }
}
