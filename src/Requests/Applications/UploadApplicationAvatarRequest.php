<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasMultipartBody;

class UploadApplicationAvatarRequest extends Request implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    public function __construct(
        private string $applicationId,
        private string $avatarPath,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/avatar";
    }

    /**
     * @return array<MultipartValue>
     */
    protected function defaultBody(): array
    {
        return [
            new MultipartValue(
                name: 'avatar',
                value: file_get_contents($this->avatarPath),
                filename: basename($this->avatarPath),
            ),
        ];
    }

    public function createDtoFromResponse(Response $response): ApplicationData
    {
        $data = $response->json('data');

        return ApplicationData::fromResponse($data['attributes'], $data['id']);
    }
}
