<?php

namespace Modules\Base\Enums;

enum BodyType: int
{
    case BUS = 0x001;
    case TRACTION = 0x002;
    case FASTBACK = 0x003;
    case PHAETON = 0x004;
    case VAN = 0x005;
    case HATCHBACK_3_DOORS = 0x006;
    case HATCHBACK_4_DOORS = 0x007;
    case HATCHBACK_5_DOORS = 0x008;
    case CONVERTIBLE = 0x009;
    case CARAVAN = 0x00A;
    case COMPACT_VAN = 0x00B;
    case COUPE = 0x00C;
    case QUAD_BIKE = 0x00D;
    case LIFTBACK = 0x00E;
    case LIMOUSINE = 0x00F;
    case MINIBUS = 0x010;
    case MICROWAVE = 0x011;
    case MINIVAN = 0x012;
    case MOPED = 0x013;
    case MOTORCYCLE = 0x014;
    case OFFROADER_3_DOORS = 0x015;
    case OFFROADER_5_DOORS = 0x016;
    case OFFROADER_OPEN = 0x017;
    case PICKUP_ONE_AND_HALF_CAB = 0x018;
    case PICKUP_DOUBLE_CAB = 0x019;
    case PICKUP_SINGLE_CAB = 0x01A;
    case GOLFER = 0x01B;
    case ROADSTER = 0x01C;
    case SEDAN = 0x01D;
    case SCOOTER = 0x01E;
    case SPEEDSTER = 0x01F;
    case SUV_COUPE = 0x020;
    case TARGA = 0x021;
    case ESTATE_3_DOORS = 0x022;
    case ESTATE_5_DOORS = 0x023;
    case TRUCK = 0x024;


    public function label(): string
    {
        return match ($this) {
            self::BUS => __('Bus'),
            self::TRACTION => __('Traction'),
            self::FASTBACK => __('Fastback'),
            self::PHAETON => __('Phaeton'),
            self::VAN => __('Van'),
            self::HATCHBACK_3_DOORS => __('Hatchback, 3 doors'),
            self::HATCHBACK_4_DOORS => __('Hatchback, 4 doors'),
            self::HATCHBACK_5_DOORS => __('Hatchback, 5 doors'),
            self::CONVERTIBLE => __('Convertible'),
            self::CARAVAN => __('Caravan'),
            self::COMPACT_VAN => __('Compact-Van'),
            self::COUPE => __('Coupe'),
            self::QUAD_BIKE => __('Quad bike'),
            self::LIFTBACK => __('Liftback'),
            self::LIMOUSINE => __('Limousine'),
            self::MINIBUS => __('Minibus'),
            self::MICROWAVE => __('Microwave'),
            self::MINIVAN => __('Minivan'),
            self::MOPED => __('Moped'),
            self::MOTORCYCLE => __('Motorcycle'),
            self::OFFROADER_3_DOORS => __('Offroader / SUV, 3 doors'),
            self::OFFROADER_5_DOORS => __('Offroader / SUV, 5 doors'),
            self::OFFROADER_OPEN => __('Offroader / SUV, open'),
            self::PICKUP_ONE_AND_HALF_CAB => __('Pickup, one and a half cab'),
            self::PICKUP_DOUBLE_CAB => __('Pickup, double cab'),
            self::PICKUP_SINGLE_CAB => __('Pickup, single cab'),
            self::GOLFER => __('Golfer'),
            self::ROADSTER => __('Roadster'),
            self::SEDAN => __('Sedan'),
            self::SCOOTER => __('Scooter'),
            self::SPEEDSTER => __('Speedster'),
            self::SUV_COUPE => __('SUV Coupe'),
            self::TARGA => __('Targa'),
            self::ESTATE_3_DOORS => __('Estate, 3 doors'),
            self::ESTATE_5_DOORS => __('Estate, 5 doors'),
            self::TRUCK => __('Truck'),
        };
    }

    public function value(): int
    {
        return $this->value;
    }
}
