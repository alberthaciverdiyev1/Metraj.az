<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class PropertyRentalDocsHiddenTest extends TestCase
{
    private function setUpAgencyPanel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        $this->actingAs(User::where('email', 'agency@metraj.az')->firstOrFail());
    }

    public function test_documents_section_hidden_when_deal_type_is_rental(): void
    {
        $this->setUpAgencyPanel();

        // deal_type filter id=2: 13 = rent_monthly (Kirayə Aylıq), 14 = rent_daily
        $component = Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 15,          // Mənzil
                'filter_2' => 13,          // Kirayə (Aylıq)
                'price_gbp' => 120000,
                'price' => 120000,
                'currency' => 'GBP',
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ]);

        $html = $component->html();

        // The "Sənədlər və İşarələr" section must NOT appear
        $this->assertStringNotContainsString('Sənədlər və İşarələr', $html);
        $this->assertStringNotContainsString('Çıxarış var (Kupça)', $html);
        $this->assertStringNotContainsString('Daxili kredit var', $html);
    }

    public function test_documents_section_visible_when_deal_type_is_sale(): void
    {
        $this->setUpAgencyPanel();

        // 12 = sale (Alış)
        $component = Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 15,          // Mənzil
                'filter_2' => 12,          // Alış
                'price_gbp' => 120000,
                'price' => 120000,
                'currency' => 'GBP',
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ]);

        $html = $component->html();

        $this->assertStringContainsString('Sənədlər və İşarələr', $html);
        $this->assertStringContainsString('Çıxarış var (Kupça)', $html);
        $this->assertStringContainsString('İpotekaya yararlı', $html);
    }

    public function test_switching_to_rental_clears_document_and_credit_toggles(): void
    {
        $this->setUpAgencyPanel();

        $component = Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 15,          // Mənzil
                'filter_2' => 12,          // Alış
                'has_document' => true,
                'has_mortgage' => true,
                'has_internal_credit' => true,
                'price_gbp' => 120000,
                'price' => 120000,
                'currency' => 'GBP',
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ]);

        // Confirm toggles were set for sale
        $data = $component->get('data');
        $this->assertTrue($data['has_document']);
        $this->assertTrue($data['has_mortgage']);

        // Switch deal type to rental (13 = Kirayə Aylıq)
        $component->set('data.filter_2', 13);

        // Toggles should now be cleared
        $data = $component->get('data');
        $this->assertFalse((bool) $data['has_document']);
        $this->assertFalse((bool) $data['has_mortgage']);
        $this->assertFalse((bool) $data['has_internal_credit']);

        // And the section is hidden
        $this->assertStringNotContainsString('Sənədlər və İşarələr', $component->html());
    }
}
