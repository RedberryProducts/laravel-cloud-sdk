<?php

namespace App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters;

use App\Data\LaravelCloud\DatabaseClusters\CreateDatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDatabaseClusterRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private CreateDatabaseClusterData $data) {}

    public function resolveEndpoint(): string
    {
        return '/databases/clusters';
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
