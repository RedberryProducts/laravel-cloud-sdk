<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetBackgroundProcessRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $backgroundProcessId) {}

    public function resolveEndpoint(): string
    {
        return "/background-processes/{$this->backgroundProcessId}";
    }

    public function createDtoFromResponse(Response $response): BackgroundProcessData
    {
        $data = $response->json('data');

        return BackgroundProcessData::fromResponse($data['attributes'], $data['id']);
    }
}
