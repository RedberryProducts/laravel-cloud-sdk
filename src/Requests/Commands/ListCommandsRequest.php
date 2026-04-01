<?php

namespace Redberry\LaravelCloudSdk\Requests\Commands;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListCommandsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/commands";
    }

    /**
     * @return Collection<int, CommandData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => CommandData::fromResponse($item['attributes'], $item['id']));
    }
}
