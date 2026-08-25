<?php

namespace Tests\Feature;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use Tests\TestCase;

class GuestPropertyAddWhatsappTest extends TestCase
{
    public function test_add_property_page_renders_whatsapp_field_and_email_info_text(): void
    {
        $response = $this->get('/add-property');

        $response->assertStatus(200);
        $response->assertSee('WhatsApp Nömrəsi');
        $response->assertSee('name="whatsapp"', false);
        $response->assertSee('Biz bunu yalnız elan statusunuzu sizə bildirmək üçün istifadə edirik');
    }

    public function test_guest_can_submit_property_with_whatsapp_number(): void
    {
        $this->withoutMiddleware();

        $city = City::first();
        $propertyType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'property_type'))->first();
        $dealType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'deal_type'))->first();

        $email = 'kamil_test_guest_' . time() . '@kibriskare.com';
        $postData = [
            'property_type_id' => $propertyType->id,
            'deal_type_id' => $dealType->id,
            'city_id' => $city->id,
            'address' => 'Bakı şəhəri, Nizami küçəsi 15',
            'price_gbp' => '120000',
            'area' => '100',
            'rooms' => 3,
            'floor' => 4,
            'total_floors' => 16,
            'advertiser' => 'owner',
            'advertiser_name' => 'Kamil Əhmədov',
            'phone' => '+994 50 777 88 99',
            'whatsapp' => '+994 50 777 88 99',
            'email' => $email,
            'description' => 'Test mənzil təsviri',
        ];

        $response = $this->postJson('/add-property', $postData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $createdProperty = Property::where('address', 'Bakı şəhəri, Nizami küçəsi 15')->latest('id')->first();
        $this->assertNotNull($createdProperty);
        $this->assertEquals('Kamil Əhmədov', $createdProperty->user->name);
        $this->assertEquals('+994 50 777 88 99', $createdProperty->agent->phone);
        $this->assertEquals('+994 50 777 88 99', $createdProperty->agent->whatsapp);
    }
}
