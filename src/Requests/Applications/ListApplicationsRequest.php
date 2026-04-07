<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListApplicationsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/applications';
    }

    /**
     * @return ApplicationData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => ApplicationData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
