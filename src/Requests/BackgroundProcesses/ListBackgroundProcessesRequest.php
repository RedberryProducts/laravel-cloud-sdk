<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListBackgroundProcessesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $instanceId) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}/background-processes";
    }

    /**
     * @return Collection<int, BackgroundProcessData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))->map(
            fn (array $item) => BackgroundProcessData::fromResponse($item['attributes'], $item['id'])
        );
    }
}
