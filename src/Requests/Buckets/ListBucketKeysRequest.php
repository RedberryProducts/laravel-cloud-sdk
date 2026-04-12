<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListBucketKeysRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $bucketId) {}

    public function resolveEndpoint(): string
    {
        return "/buckets/{$this->bucketId}/keys";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'filesystem'];
    }

    /**
     * @return BucketKeyData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            BucketKeyData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
