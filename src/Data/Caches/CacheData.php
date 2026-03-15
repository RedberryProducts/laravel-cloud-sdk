<?php

namespace App\Data\LaravelCloud\Caches;

use App\Enums\LaravelCloud\CacheSize;
use App\Enums\LaravelCloud\CacheStatus;
use App\Enums\LaravelCloud\CacheType;
use App\Enums\LaravelCloud\CloudRegion;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class CacheData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public CacheType $type,
        public CacheStatus $status,
        public CloudRegion $region,
        public CacheSize $size,
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
            type: CacheType::from($attributes['type']),
            status: CacheStatus::from($attributes['status']),
            region: CloudRegion::from($attributes['region']),
            size: CacheSize::from($attributes['size']),
            autoUpgradeEnabled: $attributes['auto_upgrade_enabled'],
            isPublic: $attributes['is_public'],
            connection: CacheConnectionData::fromResponse($attributes['connection']),
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
