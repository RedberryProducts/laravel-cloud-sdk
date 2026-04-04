<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\UpdateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBackgroundProcessRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        private string $backgroundProcessId,
        private UpdateBackgroundProcessData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/background-processes/{$this->backgroundProcessId}";
    }

    protected function defaultBody(): array
    {
        return $this->data->toArray();
    }

    public function createDtoFromResponse(Response $response): BackgroundProcessData
    {
        $data = $response->json('data');

        return BackgroundProcessData::fromResponse($data['attributes'], $data['id']);
    }
}
