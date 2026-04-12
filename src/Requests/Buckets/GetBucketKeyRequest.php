<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBucketKeyRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $keyId) {}

    public function resolveEndpoint(): string
    {
        return "/bucket-keys/{$this->keyId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'filesystem'];
    }

    public function createDtoFromResponse(Response $response): BucketKeyData
    {
        return JsonApiHydrator::hydrateOne(
            BucketKeyData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
