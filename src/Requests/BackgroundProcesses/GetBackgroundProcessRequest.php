<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBackgroundProcessRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $backgroundProcessId) {}

    public function resolveEndpoint(): string
    {
        return "/background-processes/{$this->backgroundProcessId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'instance'];
    }

    public function createDtoFromResponse(Response $response): BackgroundProcessData
    {
        return JsonApiHydrator::hydrateOne(
            BackgroundProcessData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
