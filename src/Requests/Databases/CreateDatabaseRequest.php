<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Redberry\LaravelCloudSdk\Data\Databases\CreateDatabaseData;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDatabaseRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $clusterId,
        private CreateDatabaseData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): DatabaseData
    {
        return JsonApiHydrator::hydrateOne(
            DatabaseData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}
