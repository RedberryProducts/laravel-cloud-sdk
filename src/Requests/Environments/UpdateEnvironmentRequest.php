<?php

namespace App\Http\Integrations\LaravelCloud\Requests\Environments;

use App\Data\LaravelCloud\Environments\EnvironmentData;
use App\Data\LaravelCloud\Environments\UpdateEnvironmentData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateEnvironmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $environmentId,
        private UpdateEnvironmentData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): EnvironmentData
    {
        $data = $response->json('data');

        return EnvironmentData::fromResponse($data['attributes'], $data['id']);
    }
}
