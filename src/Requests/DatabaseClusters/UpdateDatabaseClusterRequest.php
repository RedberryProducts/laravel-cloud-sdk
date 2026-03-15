<?php

namespace App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters;

use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\UpdateDatabaseClusterData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDatabaseClusterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $databaseClusterId,
        private UpdateDatabaseClusterData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): DatabaseClusterData
    {
        $data = $response->json('data');

        return DatabaseClusterData::fromResponse($data['attributes'], $data['id']);
    }
}
