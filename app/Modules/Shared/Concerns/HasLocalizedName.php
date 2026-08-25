<?php

namespace App\Modules\Shared\Concerns;

trait HasLocalizedName
{
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        if (is_array($this->name)) {
            return $this->name[$locale]
                ?? $this->name['tr']
                ?? $this->name['az']
                ?? $this->name['en']
                ?? $this->name['ru']
                ?? (string)($this->value ?? '');
        }

        return (string)($this->name ?? $this->value ?? '');
    }
}
