<?php

namespace Modules\Base\Enums;
enum RepairType: string
{
    case REPAIRED = '0x001';
    case UNREPAIRED = '0x002';


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::REPAIRED => __('Repaired'),
            self::UNREPAIRED => __('Unrepaired'),
        };
    }

    public function value(): string
    {
        return $this->value;
    }
}
