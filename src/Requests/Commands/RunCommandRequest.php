<?php

namespace Redberry\LaravelCloudSdk\Requests\Commands;

use Redberry\LaravelCloudSdk\Data\Commands\CommandData;
use Redberry\LaravelCloudSdk\Data\Commands\RunCommandData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class RunCommandRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $environmentId,
        private RunCommandData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/commands";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): CommandData
    {
        $data = $response->json('data');

        return CommandData::fromResponse($data['attributes'], $data['id']);
    }
}
