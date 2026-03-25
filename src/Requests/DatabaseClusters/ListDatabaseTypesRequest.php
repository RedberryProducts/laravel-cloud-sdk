<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListDatabaseTypesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/databases/types';
    }

    /**
     * @return Collection<int, DatabaseTypeData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => DatabaseTypeData::fromResponse($item));
    }
}
