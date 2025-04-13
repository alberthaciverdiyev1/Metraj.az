<?php

namespace Modules\Base\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use Modules\Base\Enums\BodyType;
use Modules\Base\Enums\Currency;
use Modules\Base\Enums\FuelType;
use Modules\Base\Enums\Gear;
use Modules\Base\Enums\GearBox;

#[AllowDynamicProperties] class EnumController extends Controller
{

    public function currencies()
    {
        $currencies = collect(Currency::cases())->map(function (Currency $currency) {
            return [
                'key' => $currency->name,
                'value' => $currency->value,
                'label' => $currency->label(),
            ];
        });

        return response()->json($currencies);
    }

    public function fuelTypes()
    {
        $this->fuel_types = collect(FuelType::cases())->map(function (FuelType $type) {
            return [
                'key' => $type->name,
                'value' => $type->value,
                'label' => $type->label(),
            ];
        });

        return response()->json($this->fuel_types);
    }
    public function gears()
    {
        $this->gears = collect(Gear::cases())->map(function (Gear $gear) {
            return [
                'key' => $gear->name,
                'value' => $gear->value,
                'label' => $gear->label(),
            ];
        });

        return response()->json($this->gears);
    }
    public function gearBox()
    {
        $this->gearbox = collect(GearBox::cases())->map(function (GearBox $gear) {
            return [
                'key' => $gear->name,
                'value' => $gear->value,
                'label' => $gear->label(),
            ];
        });

        return response()->json($this->gearbox);
    }
    public function bodyTypes()
    {
        $this->body_types = collect(BodyType::cases())->map(function (BodyType $type) {
            return [
                'key' => $type->name,
                'value' => $type->value,
                'label' => $type->label(),
            ];
        });

        return response()->json($this->body_types);
    }
}
