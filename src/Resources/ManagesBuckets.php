<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\UpdateBucketRequest;
use Spatie\LaravelData\Optional;

trait ManagesBuckets
{
    /**
     * @return Collection<int, BucketData>
     */
    public function buckets(): Collection
    {
        return $this->connector->send(new ListBucketsRequest)->dtoOrFail();
    }

    public function bucket(string $id): BucketData
    {
        return $this->connector->send(new GetBucketRequest($id))->dtoOrFail();
    }

    public function createBucket(
        string $name,
        string|BucketVisibility $visibility,
        string|BucketJurisdiction $jurisdiction,
        string $keyName,
        string|KeyPermission $keyPermission,
        ?array $allowedOrigins = null,
    ): BucketData {
        return $this->createBucketWith(new CreateBucketData(
            name: $name,
            visibility: $visibility,
            jurisdiction: $jurisdiction,
            keyName: $keyName,
            keyPermission: $keyPermission,
            allowedOrigins: $allowedOrigins,
        ));
    }

    public function createBucketWith(CreateBucketData $data): BucketData
    {
        return $this->connector->send(new CreateBucketRequest($data))->dtoOrFail();
    }

    public function updateBucket(
        string $id,
        string|Optional $name = new Optional,
        string|BucketVisibility|Optional $visibility = new Optional,
        array|null|Optional $allowedOrigins = new Optional,
    ): BucketData {
        return $this->updateBucketWith($id, new UpdateBucketData(
            name: $name,
            visibility: $visibility,
            allowedOrigins: $allowedOrigins,
        ));
    }

    public function updateBucketWith(string $id, UpdateBucketData $data): BucketData
    {
        return $this->connector->send(new UpdateBucketRequest($id, $data))->dtoOrFail();
    }
}
