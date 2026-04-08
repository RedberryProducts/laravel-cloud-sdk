<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteDatabaseRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private string $clusterId,
        private string $databaseId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases/{$this->databaseId}";
    }
}
