<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Tests\TestCase;

class DebugFillTest extends TestCase
{
    public function test_dump_fill_state(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('agency'));
        $this->actingAs(User::where('email', 'agency@metraj.az')->firstOrFail());

        $component = Livewire::test(\App\Filament\Agency\Resources\PropertyResource\Pages\CreateProperty::class)
            ->fillForm([
                'filter_3' => 15,
                'filter_2' => 12,
                'filter_1_city' => 1,
                'filter_1_district' => 4,
                'filter_1_metro' => 8,
                'price' => 180000,
                'currency' => 'AZN',
                'area' => 90,
                'rooms' => 3,
                'floor' => 5,
                'total_floors' => 9,
                'address' => 'İnşaatçılar prospekti 12',
                'landmark' => 'Metro yaxınlığı',
                'images' => [['url' => 'https://example.com/test.jpg', 'sort_order' => 0]],
            ]);

        $raw = $component->get('data');
        $dump = [];
        foreach ($raw as $k => $v) {
            if (str_starts_with($k, 'filter_') || in_array($k, ['price', 'rooms', 'area'])) {
                $dump[$k] = $v;
            }
        }
        fwrite(STDERR, "\nFORM STATE: " . json_encode($dump, JSON_PRETTY_PRINT) . "\n");

        $component->call('create');
        $errors = $component->get('errors');
        fwrite(STDERR, "\nVALIDATION ERRORS: " . json_encode($errors) . "\n");
        fwrite(STDERR, "\nREDIRECTED: " . ($component->get('redirectUrl') ? 'yes' : 'no') . "\n");

        $this->assertTrue(true);
    }
}
