<?php

namespace Tests\Feature;

use App\Modules\Location\Models\City;
use App\Modules\Location\Models\District;
use App\Modules\Location\Models\FilterOption;
use App\Modules\Property\Services\PropertyTitleBuilder;
use Tests\TestCase;

class PropertyTitleGenerationTest extends TestCase
{
    public function test_property_title_generation_format_sale_3_plus_1_location(): void
    {
        $propertyType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'property_type'))->first();
        $dealType = FilterOption::whereHas('filter', fn($q) => $q->where('key', 'deal_type'))->first();

        $builder = app(PropertyTitleBuilder::class);
        $title = $builder->build(
            filterOptionIds: [$propertyType->id, $dealType->id],
            rooms: 3,
            area: 120,
            landArea: null,
            location: 'Yasamal r., Bakı'
        );

        $this->assertEquals('Satılır, 3+1, Yasamal r., Bakı', $title);
    }
}
