<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseSnapshotRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $snapshotId) {}

    public function resolveEndpoint(): string
    {
        return "/database-snapshots/{$this->snapshotId}";
    }

    public function createDtoFromResponse(Response $response): DatabaseSnapshotData
    {
        $data = $response->json('data');

        return DatabaseSnapshotData::fromResponse($data['attributes'], $data['id']);
    }
}
