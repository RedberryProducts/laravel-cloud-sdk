<?php

namespace App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters;

use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseClusterRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $databaseClusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}";
    }

    public function createDtoFromResponse(Response $response): DatabaseClusterData
    {
        $data = $response->json('data');

        return DatabaseClusterData::fromResponse($data['attributes'], $data['id']);
    }
}
