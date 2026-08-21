<?php

namespace App\Core\Infrastructure\Persistence\Eloquent\Mappers;

use App\Core\Domain\Property\Entities\Property;
use App\Core\Domain\Property\Entities\PropertyAmenity;
use App\Core\Domain\Property\Entities\PropertyContact;
use App\Core\Domain\Property\Entities\PropertyFilterOption;
use App\Core\Domain\Property\Entities\PropertyImage;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agent;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Agency;
use App\Core\Infrastructure\Persistence\Eloquent\Models\Property as EloquentProperty;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Eloquent Property modelini saf domen Property entity-ə çevirir.
 * Yalnız yüklənmiş (eager loaded) əlaqələri istifadə edir; yüklənməyənlər boş qalır.
 */
final class PropertyMapper
{
    /**
     * Bir Eloquent Property modelini domen entity-ə çevirir.
     */
    public static function fromModel(EloquentProperty $model): Property
    {
        return new Property(
            id: $model->id,
            code: (string) $model->code,
            slug: (string) $model->slug,
            title: (string) $model->title,
            description: (string) $model->description,
            sellerType: $model->seller_type,
            hasDocument: (bool) $model->has_document,
            hasMortgage: (bool) $model->has_mortgage,
            hasInternalCredit: (bool) $model->has_internal_credit,
            price: (float) $model->price,
            currency: (string) ($model->currency ?? 'AZN'),
            prices: is_array($model->prices) ? $model->prices : [],
            area: $model->area !== null ? (int) $model->area : null,
            landArea: $model->land_area !== null ? (int) $model->land_area : null,
            rooms: $model->rooms !== null ? (int) $model->rooms : null,
            floor: $model->floor !== null ? (int) $model->floor : null,
            totalFloors: $model->total_floors !== null ? (int) $model->total_floors : null,
            cityId: $model->city_id !== null ? (int) $model->city_id : null,
            districtId: $model->district_id !== null ? (int) $model->district_id : null,
            landmark: $model->landmark,
            address: $model->address,
            latitude: $model->latitude !== null ? (float) $model->latitude : null,
            longitude: $model->longitude !== null ? (float) $model->longitude : null,
            agencyId: $model->agency_id !== null ? (int) $model->agency_id : null,
            agentId: $model->agent_id !== null ? (int) $model->agent_id : null,
            userId: $model->user_id !== null ? (int) $model->user_id : null,
            status: $model->status,
            isFeatured: (bool) $model->is_featured,
            isVip: (bool) $model->is_vip,
            viewsCount: (int) $model->views_count,
            createdAt: $model->created_at ? DateTimeImmutable::createFromInterface($model->created_at) : null,
            updatedAt: $model->updated_at ? DateTimeImmutable::createFromInterface($model->updated_at) : null,
            images: self::mapImages($model),
            filterOptions: self::mapFilterOptions($model),
            amenities: self::mapAmenities($model),
            agent: self::mapAgent($model),
            agency: self::mapAgency($model),
            owner: self::mapOwner($model),
            cityName: self::loaded($model, 'city')?->getAttribute('name') ?: null,
            districtName: self::loaded($model, 'district')?->getAttribute('name') ?: null,
        );
    }

    /**
     * Bir Eloquent Collection-nı domen entity massivinə çevirir.
     *
     * @param  Collection<int, EloquentProperty>  $models
     * @return Property[]
     */
    public static function fromCollection(Collection $models): array
    {
        return $models->map(fn (EloquentProperty $model) => self::fromModel($model))->all();
    }

    /**
     * Yüklənmiş əlaqəni qaytarır; yüklənməyibsə boş collection.
     *
     * @return Collection|Model|null
     */
    private static function loaded(Model $model, string $relation): Model|Collection|null
    {
        if (!$model->relationLoaded($relation)) {
            return null;
        }

        return $model->getRelation($relation);
    }

    /**
     * @return PropertyImage[]
     */
    private static function mapImages(EloquentProperty $model): array
    {
        $images = self::loaded($model, 'images');

        if (!$images instanceof Collection) {
            return [];
        }

        return $images->map(fn ($image) => new PropertyImage(
            id: (int) $image->id,
            url: (string) $image->url,
            sortOrder: (int) ($image->sort_order ?? 0),
        ))->values()->all();
    }

    /**
     * @return PropertyFilterOption[]
     */
    private static function mapFilterOptions(EloquentProperty $model): array
    {
        $options = self::loaded($model, 'filterOptions');

        if (!$options instanceof Collection) {
            return [];
        }

        return $options->map(function ($option) {
            $filter = $option->relationLoaded('filter') ? $option->filter : null;

            return new PropertyFilterOption(
                id: (int) $option->id,
                filterId: (int) $option->filter_id,
                filterKey: $filter?->key,
                value: (string) $option->value,
                name: is_array($option->name) ? $option->name : [],
                parentId: $option->parent_id !== null ? (int) $option->parent_id : null,
            );
        })->values()->all();
    }

    /**
     * @return PropertyAmenity[]
     */
    private static function mapAmenities(EloquentProperty $model): array
    {
        $amenities = self::loaded($model, 'amenities');

        if (!$amenities instanceof Collection) {
            return [];
        }

        return $amenities->map(fn ($amenity) => new PropertyAmenity(
            id: (int) $amenity->id,
            name: is_array($amenity->name) ? $amenity->name : ['az' => (string) $amenity->name],
            icon: $amenity->icon,
        ))->values()->all();
    }

    private static function mapAgent(EloquentProperty $model): ?PropertyContact
    {
        $agent = self::loaded($model, 'agent');

        if (!$agent instanceof Agent) {
            return null;
        }

        $user = $agent->relationLoaded('user') ? $agent->user : null;

        return new PropertyContact(
            id: (int) $agent->id,
            type: PropertyContact::TYPE_AGENT,
            name: $user?->name ?? $agent->name ?? null,
            phone: $agent->phone,
            avatar: $agent->avatar,
        );
    }

    private static function mapAgency(EloquentProperty $model): ?PropertyContact
    {
        $agency = self::loaded($model, 'agency');

        if (!$agency instanceof Agency) {
            return null;
        }

        return new PropertyContact(
            id: (int) $agency->id,
            type: PropertyContact::TYPE_AGENCY,
            name: $agency->name,
            phone: $agency->phone,
            logo: $agency->logo,
            isVerified: (bool) $agency->is_verified,
        );
    }

    private static function mapOwner(EloquentProperty $model): ?PropertyContact
    {
        $user = self::loaded($model, 'user');

        if (!$user instanceof \App\Models\User) {
            return null;
        }

        return new PropertyContact(
            id: (int) $user->id,
            type: PropertyContact::TYPE_OWNER,
            name: $user->name,
            phone: $user->phone ?? null,
            avatar: $user->avatar ?? null,
        );
    }
}
