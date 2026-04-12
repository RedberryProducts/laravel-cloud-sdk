<?php

namespace Redberry\LaravelCloudSdk\Requests\Commands;

use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetCommandRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $commandId) {}

    public function resolveEndpoint(): string
    {
        return "/commands/{$this->commandId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'environment,deployment,initiator'];
    }

    public function createDtoFromResponse(Response $response): CommandData
    {
        return JsonApiHydrator::hydrateOne(
            CommandData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
