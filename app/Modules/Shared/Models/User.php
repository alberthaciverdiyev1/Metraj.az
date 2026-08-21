<?php

namespace App\Modules\Shared\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Modules\Agency\Models\Agency;
use App\Modules\Agency\Models\Agent;
use App\Modules\Property\Models\Property;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Agency\Models\Agent|null $agent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Agency\Models\Agency> $agencies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Modules\Property\Models\Property> $properties
 */
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ADMIN_EMAIL = 'admin@metraj.az';

    /**
     * Kütləvi doldurula bilən sütunlar
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Serializasiyada gizlədilən atributlar
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Filament panellərinə giriş icazəsi.
     * Admin panelinə yalnız admin istifadəçi daxil ola bilər.
     * Agentlik paneli bütün istifadəçilər üçün açıqdır — tenant scoping
     * (Agency/Agent/Property resource) məlumatların yalnız sahibinə
     * göstərilməsini təmin edir.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->email === self::ADMIN_EMAIL;
        }

        // Agentlik paneli — hər bir yeni yaradılmış istifadəçi daxil ola bilər
        return true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * İstifadəçinin rieltor (agent) qeydi (varsa)
     */
    public function agent(): HasOne
    {
        return $this->hasOne(Agent::class, 'user_id');
    }

    /**
     * İstifadəçinin sahibi olduğu agentliklər
     */
    public function agencies(): HasMany
    {
        return $this->hasMany(Agency::class, 'owner_id');
    }

    /**
     * Rieltorun aid olduğu agentlik (agent profilindən keçərək)
     */
    public function agency(): HasOneThrough
    {
        return $this->hasOneThrough(
            Agency::class,
            Agent::class,
            'user_id',   // agents.user_id
            'id',        // agencies.id
            'id',        // users.id
            'agency_id'  // agents.agency_id
        );
    }

    /**
     * İstifadəçinin "tenant" agentliyi.
     * Agentlik sahibi üçün sahib olduğu agentlik, rieltor üçün isə
     * aid olduğu agentlik qaytarılır.
     */
    public function tenantAgency(): ?Agency
    {
        if ($this->agencies()->exists()) {
            return $this->agencies()->first();
        }

        return $this->agent?->agency;
    }

    /**
     * İstifadəçinin agentlik sahibi olub-olmadığını yoxlayır.
     * Sahib agentlikləri idarə edə bilər; rieltor isə yalnız öz elanlarını.
     */
    public function isTenantOwner(): bool
    {
        return $this->agencies()->exists();
    }

    /**
     * İstifadəçinin yerləşdirdiyi bütün elanlar
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'user_id');
    }
}
