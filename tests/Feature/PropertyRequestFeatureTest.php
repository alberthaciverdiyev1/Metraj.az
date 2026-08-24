<?php

namespace Tests\Feature;

use App\Modules\Location\Models\City;
use App\Modules\PropertyRequest\Enums\RequestType;
use App\Modules\PropertyRequest\Models\PropertyRequest;
use Tests\TestCase;

class PropertyRequestFeatureTest extends TestCase
{
    public function test_property_requests_catalog_page_renders_successfully(): void
    {
        $response = $this->get('/axtariram');

        $response->assertStatus(200);
        $response->assertSee('Tələb və İstək Elanları');
        $response->assertSee('Almaq İstəyirəm');
        $response->assertSee('Kirayə Axtarıram');
        $response->assertSee('Günlük');
        $response->assertSee('Otaq Yoldaşı');
    }

    public function test_property_request_create_page_renders_successfully(): void
    {
        $response = $this->get('/axtariram/elan-ver');

        $response->assertStatus(200);
        $response->assertSee('Tələb Elanı Yerləşdir');
        $response->assertSee('Almaq İstəyirəm');
        $response->assertSee('Kirayə Axtarıram');
    }

    public function test_can_submit_purchase_demand_request(): void
    {
        $this->withoutMiddleware();

        $city = City::first();
        if (! $city) {
            $city = City::create([
                'name' => ['az' => 'Bakı'],
                'slug' => 'baki',
                'is_active' => true,
            ]);
        }

        $postData = [
            'request_type' => 'buy',
            'property_type' => 'Mənzil',
            'title' => 'Nərimanovda 3 otaqlı kupçalı və ipotekaya yararlı mənzil axtarıram',
            'description' => 'Təmirli və ya səliqəli təmirli olsun. Metroya piyada məsafədə olması vacibdir.',
            'budget_max' => 160000,
            'rooms' => '3',
            'city_id' => $city->id,
            'location_note' => 'Nərimanov m/s yaxınlığı',
            'has_deed' => 1,
            'mortgage_eligible' => 1,
            'contact_name' => 'Kənan Qasımov',
            'contact_phone' => '+994509998877',
            'contact_whatsapp' => '+994509998877',
        ];

        $response = $this->withoutMiddleware()->post('/axtariram/elan-ver', $postData);

        if ($response->status() !== 302) {
            $response->dumpSession();
        }

        $this->assertDatabaseHas('property_requests', [
            'title' => 'Nərimanovda 3 otaqlı kupçalı və ipotekaya yararlı mənzil axtarıram',
            'request_type' => 'buy',
            'budget_max' => 160000,
            'has_deed' => 1,
            'mortgage_eligible' => 1,
        ]);

        $requestRecord = PropertyRequest::where('title', 'Nərimanovda 3 otaqlı kupçalı və ipotekaya yararlı mənzil axtarıram')->latest('id')->first();
        $this->assertNotNull($requestRecord);

        $response->assertRedirect(route('requests.show', $requestRecord->slug));
    }

    public function test_can_submit_rental_demand_request(): void
    {
        $this->withoutMiddleware();

        $city = City::first();
        $postData = [
            'request_type' => 'rent_monthly',
            'property_type' => 'Mənzil',
            'title' => 'Gənclik m/s yaxınlığında ailə üçün 2 otaqlı kirayə ev axtarılır',
            'description' => 'Ailə üçün uzunmüddətli kirayə ev axtarılır. Əşyalı olması və kombi sistemi olması arzuolunandır.',
            'budget_max' => 600,
            'rooms' => '2',
            'city_id' => $city?->id ?? 1,
            'occupancy_type' => 'Ailə',
            'bills_included' => 1,
            'contact_name' => 'Elmir Rəhimov',
            'contact_phone' => '+994551112233',
        ];

        $response = $this->post('/axtariram/elan-ver', $postData);

        $this->assertDatabaseHas('property_requests', [
            'title' => 'Gənclik m/s yaxınlığında ailə üçün 2 otaqlı kirayə ev axtarılır',
            'request_type' => 'rent_monthly',
            'budget_max' => 600,
            'bills_included' => 1,
        ]);
    }

    public function test_property_request_details_page_renders_and_increments_views(): void
    {
        $city = City::first();
        $requestRecord = PropertyRequest::create([
            'request_type' => RequestType::Buy,
            'property_type' => 'Villa',
            'title' => 'Mərdəkanda bağ evi almaq istəyirəm ' . time(),
            'description' => 'Həyətində hovuzu olan və çıxarışlı bağ evi axtarıram.',
            'budget_max' => 250000,
            'city_id' => $city?->id,
            'contact_name' => 'Samir Əhmədov',
            'contact_phone' => '+994701234567',
        ]);

        $initialViews = $requestRecord->views_count;

        $response = $this->get('/axtariram/' . $requestRecord->slug);

        $response->assertStatus(200);
        $response->assertSee($requestRecord->title);
        $response->assertSee('250 000 ₼');
        $response->assertSee('Samir Əhmədov');

        $this->assertEquals($initialViews + 1, $requestRecord->fresh()->views_count);
    }
}
