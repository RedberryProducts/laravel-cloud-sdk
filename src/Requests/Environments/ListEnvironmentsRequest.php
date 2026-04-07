<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListEnvironmentsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/environments";
    }

    /**
     * @return EnvironmentData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => EnvironmentData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}
