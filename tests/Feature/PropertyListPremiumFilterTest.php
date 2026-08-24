<?php

namespace Tests\Feature;

use App\Modules\Property\DTOs\PropertyFilterDTO;
use Tests\TestCase;

class PropertyListPremiumFilterTest extends TestCase
{
    public function test_no_filter_returns_30_items_with_top_10_premium_first_and_fills_missing_with_regular(): void
    {
        $response = $this->get('/listing');

        $response->assertStatus(200);
        $response->assertViewHas('properties');

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $properties */
        $properties = $response->viewData('properties');

        $this->assertEquals(30, $properties->perPage());

        $items = $properties->items();
        
        $premiumCount = 0;
        $regularCount = 0;
        $seenRegular = false;
        foreach ($items as $idx => $property) {
            $isPremium = (bool) ($property->is_vip || $property->is_featured);
            if ($isPremium) {
                $premiumCount++;
                $this->assertFalse($seenRegular, "Premium item at index {$idx} should not appear after regular items");
            } else {
                $regularCount++;
                $seenRegular = true;
            }
        }

        // Must not exceed 10 premium items per page
        $this->assertLessThanOrEqual(10, $premiumCount);

        // If total matching is >= 30, the page must have 30 items
        if ($properties->total() >= 30) {
            $this->assertCount(30, $items);
            $this->assertEquals(30 - $premiumCount, $regularCount);
        }
    }

    public function test_filtered_results_contain_up_to_10_premium_first_and_fills_missing_with_regular(): void
    {
        $response = $this->get('/listing?adType=sale');

        $response->assertStatus(200);
        $response->assertViewHas('properties');

        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $properties */
        $properties = $response->viewData('properties');

        $this->assertEquals(30, $properties->perPage());

        $items = $properties->items();
        
        $premiumCount = 0;
        $regularCount = 0;
        $seenRegular = false;
        foreach ($items as $idx => $property) {
            $isPremium = (bool) ($property->is_vip || $property->is_featured);
            if ($isPremium) {
                $premiumCount++;
                $this->assertFalse($seenRegular, "Premium item at index {$idx} should not appear after regular items");
            } else {
                $regularCount++;
                $seenRegular = true;
            }
        }

        // Must not exceed 10 premium items per page
        $this->assertLessThanOrEqual(10, $premiumCount);

        // If total matching is >= 30, the page must have 30 items
        if ($properties->total() >= 30) {
            $this->assertCount(30, $items);
            $this->assertEquals(30 - $premiumCount, $regularCount);
        }
    }

    public function test_ajax_response_returns_single_properties_variable_without_separate_premium(): void
    {
        $response = $this->getJson('/listing');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'properties',
            'pagination',
            'total',
        ]);

        $response->assertJsonMissingPath('premium');
    }

    public function test_property_filter_dto_has_filters_detection(): void
    {
        $emptyDto = PropertyFilterDTO::fromArray([]);
        $this->assertFalse($emptyDto->hasFilters());

        $allTypeDto = PropertyFilterDTO::fromArray(['adType' => 'all']);
        $this->assertFalse($allTypeDto->hasFilters());

        $saleDto = PropertyFilterDTO::fromArray(['adType' => 'sale']);
        $this->assertTrue($saleDto->hasFilters());

        $roomDto = PropertyFilterDTO::fromArray(['roomCount' => '3']);
        $this->assertTrue($roomDto->hasFilters());

        $priceDto = PropertyFilterDTO::fromArray(['minPrice' => '50000']);
        $this->assertTrue($priceDto->hasFilters());
    }
}
