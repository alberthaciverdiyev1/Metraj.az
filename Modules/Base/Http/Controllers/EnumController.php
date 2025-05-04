<?php

namespace Modules\Base\Http\Controllers;

use AllowDynamicProperties;
use Modules\Base\Enums\PropertyType;
use Modules\Base\Enums\Currency;
use Modules\Base\Enums\RepairType;
use Illuminate\Routing\Controller;
use Modules\Base\Enums\RoomCount;

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

    public function propertyTypes()
    {
        $this->property_types = collect(PropertyType::cases())->map(function (PropertyType $type) {
            return [
                'key' => $type->name,
                'value' => $type->value,
                'label' => $type->label(),
            ];
        });

        return response()->json($this->property_types);
    }
    public function repairTypes()
    {
        $this->repairTypes = collect(RepairType::cases())->map(function (RepairType $gear) {
            return [
                'key' => $gear->name,
                'value' => $gear->value,
                'label' => $gear->label(),
            ];
        });

        return response()->json($this->repairTypes);
    }
    public function roomCount()
    {
        $this->roomCount = collect(RoomCount::cases())->map(function (RoomCount $gear) {
            return [
                'key' => $gear->name,
                'value' => $gear->value,
                'label' => $gear->label(),
            ];
        });

        return response()->json($this->roomCount);
    }
}
