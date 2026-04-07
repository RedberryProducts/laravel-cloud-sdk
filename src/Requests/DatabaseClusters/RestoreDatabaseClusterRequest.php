<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\RestoreDatabaseClusterData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RestoreDatabaseClusterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $databaseClusterId,
        private RestoreDatabaseClusterData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}/restore";
    }

    public function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): DatabaseClusterData
    {
        $data = $response->json('data');

        return DatabaseClusterData::fromResponse($data['attributes'], $data['id']);
    }
}
