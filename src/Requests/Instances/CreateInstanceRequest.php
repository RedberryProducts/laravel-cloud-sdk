<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Instances;

use App\Data\LaravelCloud\Instances\CreateInstanceData;
use App\Data\LaravelCloud\Instances\InstanceData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateInstanceRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $environmentId,
        private CreateInstanceData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/instances";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): InstanceData
    {
        $data = $response->json('data');

        return InstanceData::fromResponse($data['attributes'], $data['id']);
    }
}
