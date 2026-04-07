<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Redberry\LaravelCloudSdk\Requests\Caches\CreateCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\DeleteCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\GetCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCacheTypesRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\UpdateCacheRequest;
use Spatie\LaravelData\Optional;

trait ManagesCaches
{
    /**
     * @return LazyCollection<int, CacheData>
     */
    public function caches(): LazyCollection
    {
        return $this->connector->paginate(new ListCachesRequest)->collect();
    }

    public function cache(string $id): CacheData
    {
        return $this->connector->send(new GetCacheRequest($id))->dtoOrFail();
    }

    public function createCache(
        string|CacheType $type,
        string $name,
        string|CloudRegion $region,
        string|CacheSize $size,
        bool $autoUpgradeEnabled,
        bool $isPublic,
        string|EvictionPolicy|null $evictionPolicy = null,
    ): CacheData {
        return $this->createCacheWith(new CreateCacheData(
            type: $type,
            name: $name,
            region: $region,
            size: $size,
            autoUpgradeEnabled: $autoUpgradeEnabled,
            isPublic: $isPublic,
            evictionPolicy: $evictionPolicy,
        ));
    }

    public function createCacheWith(CreateCacheData $data): CacheData
    {
        return $this->connector->send(new CreateCacheRequest($data))->dtoOrFail();
    }

    public function updateCache(
        string $id,
        string|Optional $name = new Optional,
        string|CacheSize|Optional $size = new Optional,
        bool|Optional $autoUpgradeEnabled = new Optional,
        bool|Optional $isPublic = new Optional,
        string|EvictionPolicy|null|Optional $evictionPolicy = new Optional,
    ): CacheData {
        return $this->updateCacheWith($id, new UpdateCacheData(
            name: $name,
            size: $size,
            autoUpgradeEnabled: $autoUpgradeEnabled,
            isPublic: $isPublic,
            evictionPolicy: $evictionPolicy,
        ));
    }

    public function updateCacheWith(string $id, UpdateCacheData $data): CacheData
    {
        return $this->connector->send(new UpdateCacheRequest($id, $data))->dtoOrFail();
    }

    public function cacheTypes(): Collection
    {
        return $this->connector->send(new ListCacheTypesRequest)->dtoOrFail();
    }

    public function deleteCache(string $id): void
    {
        $this->connector->send(new DeleteCacheRequest($id))->throw();
    }
}
