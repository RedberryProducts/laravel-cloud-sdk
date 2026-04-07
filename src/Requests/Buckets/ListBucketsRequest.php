<?php

namespace Redberry\LaravelCloudSdk\Requests\Buckets;

use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListBucketsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/buckets';
    }

    /**
     * @return BucketData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => BucketData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
