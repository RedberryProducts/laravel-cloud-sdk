<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\UpdateBucketKeyRequest;

trait ManagesBucketKeys
{
    /**
     * @return LazyCollection<int, BucketKeyData>
     */
    public function bucketKeys(string $bucketId): LazyCollection
    {
        return $this->connector->paginate(new ListBucketKeysRequest($bucketId))->collect();
    }

    public function bucketKey(string $keyId): BucketKeyData
    {
        return $this->connector->send(new GetBucketKeyRequest($keyId))->dtoOrFail();
    }

    public function createBucketKey(
        string $bucketId,
        string $name,
        string|KeyPermission $permission,
    ): BucketKeyData {
        return $this->createBucketKeyWith($bucketId, new CreateBucketKeyData(
            name: $name,
            permission: $permission,
        ));
    }

    public function createBucketKeyWith(string $bucketId, CreateBucketKeyData $data): BucketKeyData
    {
        return $this->connector->send(new CreateBucketKeyRequest($bucketId, $data))->dtoOrFail();
    }

    public function updateBucketKey(string $keyId, string $name): BucketKeyData
    {
        return $this->updateBucketKeyWith($keyId, new UpdateBucketKeyData(name: $name));
    }

    public function updateBucketKeyWith(string $keyId, UpdateBucketKeyData $data): BucketKeyData
    {
        return $this->connector->send(new UpdateBucketKeyRequest($keyId, $data))->dtoOrFail();
    }
}
