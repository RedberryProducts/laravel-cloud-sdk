<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $clusterId,
        private string $databaseId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases/{$this->databaseId}";
    }

    public function createDtoFromResponse(Response $response): DatabaseData
    {
        $data = $response->json('data');

        return DatabaseData::fromResponse($data['attributes'], $data['id']);
    }
}
