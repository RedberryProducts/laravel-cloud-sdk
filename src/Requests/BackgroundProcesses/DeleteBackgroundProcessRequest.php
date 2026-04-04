<?php

namespace Redberry\LaravelCloudSdk\Requests\BackgroundProcesses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteBackgroundProcessRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $backgroundProcessId) {}

    public function resolveEndpoint(): string
    {
        return "/background-processes/{$this->backgroundProcessId}";
    }
}
