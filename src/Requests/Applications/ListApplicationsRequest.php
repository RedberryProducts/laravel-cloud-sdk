<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListApplicationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/applications';
    }

    /**
     * @return Collection<int, ApplicationData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => ApplicationData::fromResponse($item['attributes'], $item['id']));
    }
}
