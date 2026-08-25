<?php

namespace Tests\Feature;

use App\Modules\Property\Models\Property;
use App\Modules\Shared\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class PropertyCascadeFormTest extends TestCase
{
    private function owner(): User
    {
        return User::where('email', 'agency@kibriskare.com')->firstOrFail();
    }

    private function setUpAgencyPanel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('agency'));
    }

    public function test_cascade_location_and_title_generation(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        // Option IDs from seeder:
        // property_type: 15=apartment(Mənzil), 16=house, 17=office, 18=garage, 19=land, 20=commercial
        // deal_type: 12=sale(Alış), 13=rent_monthly, 14=rent_daily
        // location: 1=Bakı, 4=Yasamal, 8=Elmlər Akademiyası m/s
        Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 15,        // Mənzil
                'filter_2' => 12,        // Alış
                'filter_1_city' => 1,     // Bakı
                'filter_1_district' => 4, // Yasamal
                'filter_1_metro' => 8,    // Elmlər Akademiyası m/s
                'price' => 180000,
                'currency' => 'AZN',
                'area' => 90,
                'rooms' => 3,
                'floor' => 5,
                'total_floors' => 9,
                'address' => 'İnşaatçılar prospekti 12',
                'landmark' => 'Metro yaxınlığı',
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $prop = Property::where('user_id', $this->owner()->id)->latest('id')->first();
        $this->assertNotNull($prop, 'Property should be created');

        // Location cascade: city + district + metro all synced
        $locationValues = $prop->filterOptions
            ->where('filter_id', 1)
            ->pluck('value')
            ->sort()
            ->values()
            ->all();

        $this->assertEquals(['baku', 'elmler_akademiyasi', 'yasamal'], $locationValues);

        // Title must contain the most specific (deepest) location
        $this->assertStringContainsString('Elmlər Akademiyası m/s', $prop->title);

        // Property type + deal type synced
        $this->assertTrue($prop->filterOptions->where('filter_id', 3)->where('id', 15)->isNotEmpty());
        $this->assertTrue($prop->filterOptions->where('filter_id', 2)->where('id', 12)->isNotEmpty());

        // cleanup
        $prop->filterOptions()->detach();
        $prop->images()->delete();
        $prop->forceDelete();
    }

    public function test_land_property_shows_land_area_instead_of_area(): void
    {
        $this->setUpAgencyPanel();
        $this->actingAs($this->owner());

        Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 19,        // Torpaq
                'filter_2' => 12,        // Alış
                'price' => 50000,
                'currency' => 'AZN',
                'land_area' => 10,
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $prop = Property::where('user_id', $this->owner()->id)->latest('id')->first();
        $this->assertNotNull($prop);
        $this->assertEquals(10, $prop->land_area);
        $this->assertStringContainsString('10 sot', $prop->title);
        $this->assertStringContainsString('torpaq', mb_strtolower($prop->title));

        // cleanup
        $prop->filterOptions()->detach();
        $prop->images()->delete();
        $prop->forceDelete();
    }
}
