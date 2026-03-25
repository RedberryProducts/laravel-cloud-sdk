<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListEnvironmentsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/environments";
    }

    /**
     * @return Collection<int, EnvironmentData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => EnvironmentData::fromResponse($item['attributes'], $item['id']));
    }
}
