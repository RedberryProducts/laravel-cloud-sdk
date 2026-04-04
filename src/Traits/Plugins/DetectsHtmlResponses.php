<?php

namespace Redberry\LaravelCloudSdk\Traits\Plugins;

use Saloon\Http\Response;

trait DetectsHtmlResponses
{
    protected function isHtmlResponse(Response $response): bool
    {
        return str_contains($response->header('Content-Type') ?? '', 'text/html');
    }

    public function hasRequestFailed(Response $response): ?bool
    {
        return $this->isHtmlResponse($response) ? true : null;
    }
}
