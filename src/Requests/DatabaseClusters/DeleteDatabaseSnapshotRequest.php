<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteDatabaseSnapshotRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $snapshotId) {}

    public function resolveEndpoint(): string
    {
        return "/database-snapshots/{$this->snapshotId}";
    }
}
