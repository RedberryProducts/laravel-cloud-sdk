<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBucketRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $bucketId) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'keys'];
    }

    public function createDtoFromResponse(Response $response): BucketData
    {
        return JsonApiHydrator::hydrateOne(
            BucketData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
