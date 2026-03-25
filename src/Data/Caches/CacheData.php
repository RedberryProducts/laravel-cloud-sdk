<?php

namespace Redberry\LaravelCloudSdk\Data\Caches;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheStatus;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Spatie\LaravelData\Data;

class CacheData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string|CacheType $type,
        public string|CacheStatus $status,
        public string|CloudRegion $region,
        public string|CacheSize $size,
        public bool $autoUpgradeEnabled,
        public bool $isPublic,
        public CacheConnectionData $connection,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            type: CacheType::tryFrom($attributes['type']) ?? $attributes['type'],
            status: CacheStatus::tryFrom($attributes['status']) ?? $attributes['status'],
            region: CloudRegion::tryFrom($attributes['region']) ?? $attributes['region'],
            size: CacheSize::tryFrom($attributes['size']) ?? $attributes['size'],
            autoUpgradeEnabled: $attributes['auto_upgrade_enabled'],
            isPublic: $attributes['is_public'],
            connection: CacheConnectionData::fromResponse($attributes['connection']),
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
