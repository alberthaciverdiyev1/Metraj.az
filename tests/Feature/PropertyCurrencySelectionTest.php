<?php

namespace Tests\Feature;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Models\Property;
use Tests\TestCase;

class PropertyCurrencySelectionTest extends TestCase
{
    public function test_add_property_page_renders_currency_dropdown_with_gbp_default(): void
    {
        $response = $this->get('/add-property');

        $response->assertStatus(200);
        $response->assertSee('name="currency"', false);
        $response->assertSee('value="GBP"', false);
        $response->assertSee('value="AZN"', false);
        $response->assertSee('value="USD"', false);
    }

    public function test_can_submit_property_with_custom_currency_azn(): void
    {
        $this->withoutMiddleware();

        $city = City::first();
        $propertyType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'property_type'))->first();
        $dealType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'deal_type'))->first();

        $email = 'currency_test_' . time() . '@metraj.az';
        $postData = [
            'property_type_id' => $propertyType->id,
            'deal_type_id' => $dealType->id,
            'city_id' => $city->id,
            'address' => 'Bakı ş., Valyuta Test Küçəsi 1',
            'price' => '221000',
            'currency' => 'AZN',
            'area' => '90',
            'rooms' => 3,
            'floor' => 2,
            'total_floors' => 10,
            'advertiser' => 'owner',
            'advertiser_name' => 'Elmir Həsənov',
            'phone' => '+994 55 999 11 22',
            'email' => $email,
        ];

        $response = $this->postJson('/add-property', $postData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $createdProperty = Property::where('address', 'Bakı ş., Valyuta Test Küçəsi 1')->latest('id')->first();
        $this->assertNotNull($createdProperty);
        $this->assertEquals('AZN', $createdProperty->currency);
        $this->assertIsArray($createdProperty->prices);
        $this->assertEquals(221000, $createdProperty->prices['AZN']);
        $this->assertGreaterThan(0, (float) $createdProperty->price);
    }

    public function test_can_submit_property_with_default_currency_gbp(): void
    {
        $this->withoutMiddleware();

        $city = City::first();
        $propertyType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'property_type'))->first();
        $dealType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'deal_type'))->first();

        $email = 'currency_gbp_test_' . time() . '@metraj.az';
        $postData = [
            'property_type_id' => $propertyType->id,
            'deal_type_id' => $dealType->id,
            'city_id' => $city->id,
            'address' => 'Bakı ş., GBP Test Küçəsi 2',
            'price' => '150000',
            'currency' => 'GBP',
            'area' => '120',
            'rooms' => 4,
            'floor' => 5,
            'total_floors' => 12,
            'advertiser' => 'owner',
            'advertiser_name' => 'Kənan Məmmədov',
            'phone' => '+994 50 123 45 67',
            'email' => $email,
        ];

        $response = $this->postJson('/add-property', $postData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $createdProperty = Property::where('address', 'Bakı ş., GBP Test Küçəsi 2')->latest('id')->first();
        $this->assertNotNull($createdProperty);
        $this->assertEquals('GBP', $createdProperty->currency);
        $this->assertEquals(150000, $createdProperty->price);
        $this->assertIsArray($createdProperty->prices);
        $this->assertEquals(150000, $createdProperty->prices['GBP']);
        $this->assertGreaterThan(150000, $createdProperty->prices['AZN']);
    }
}
