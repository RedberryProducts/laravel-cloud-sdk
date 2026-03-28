<?php

namespace Redberry\LaravelCloudSdk\Requests\Domains;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteDomainRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $domainId) {}

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}";
    }
}
