<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Databases;

use App\Data\LaravelCloud\Databases\CreateDatabaseData;
use App\Data\LaravelCloud\Databases\DatabaseData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateDatabaseRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $clusterId,
        private CreateDatabaseData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): DatabaseData
    {
        $data = $response->json('data');

        return DatabaseData::fromResponse($data['attributes'], $data['id']);
    }
}
