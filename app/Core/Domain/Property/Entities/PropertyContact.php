<?php

namespace App\Core\Domain\Property\Entities;

/**
 * Əmlakın satıcı/təmsilçi əlaqə məlumatının saf domen obyekti.
 * agent, agency və owner (mülkiyyətçi) üçün vahid tip kimi istifadə olunur.
 * name alanı: agent üçün user->name, agency üçün agency->name, owner üçün user->name.
 */
final readonly class PropertyContact
{
    public const string TYPE_AGENT = 'agent';
    public const string TYPE_AGENCY = 'agency';
    public const string TYPE_OWNER = 'owner';

    public function __construct(
        public int $id,
        public string $type,
        public ?string $name = null,
        public ?string $phone = null,
        public ?string $avatar = null,
        public ?string $logo = null,
        public bool $isVerified = false,
    ) {}
}
