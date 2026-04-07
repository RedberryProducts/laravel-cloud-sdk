<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDatabasesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases";
    }

    /**
     * @return DatabaseData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => DatabaseData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
