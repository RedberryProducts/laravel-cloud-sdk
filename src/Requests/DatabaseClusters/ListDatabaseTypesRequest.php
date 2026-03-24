<?php

namespace App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters;

use App\Data\LaravelCloud\DatabaseClusters\DatabaseTypeData;
use Illuminate\Support\Collection;
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
