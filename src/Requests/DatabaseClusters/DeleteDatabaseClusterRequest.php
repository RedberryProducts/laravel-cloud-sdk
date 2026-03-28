<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteDatabaseClusterRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $databaseClusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}";
    }
}
