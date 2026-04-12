<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\CreateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateBackgroundProcessRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $instanceId,
        private CreateBackgroundProcessData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}/background-processes";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
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
