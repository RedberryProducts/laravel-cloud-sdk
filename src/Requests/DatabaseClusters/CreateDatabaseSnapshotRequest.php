<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDatabaseSnapshotRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $databaseClusterId,
        private CreateDatabaseSnapshotData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}/snapshots";
    }

    public function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): DatabaseSnapshotData
    {
        $data = $response->json('data');

        return DatabaseSnapshotData::fromResponse($data['attributes'], $data['id']);
    }
}
