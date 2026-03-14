<?php

namespace App\Http\Integrations\LaravelCloud\Auth;

use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

class LaravelCloudTokenAuthenticator implements Authenticator
{
    public function __construct(private string $token) {}

    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Authorization', 'Bearer '.$this->token);
    }
}
