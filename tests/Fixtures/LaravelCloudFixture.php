<?php

namespace Tests\Fixtures\LaravelCloud;

use Saloon\Http\Faking\Fixture;

class LaravelCloudFixture extends Fixture
{
    public function __construct(string $fixtureName)
    {
        parent::__construct("laravel-cloud/{$fixtureName}");
    }

    /**
     * @return array<string, string>
     */
    protected function defineSensitiveHeaders(): array
    {
        return [
            'set-cookie' => 'REDACTED',
            'x-amzn-requestid' => 'REDACTED',
            'x-amzn-trace-id' => 'REDACTED',
            'x-amz-cf-id' => 'REDACTED',
            'x-amz-apigw-id' => 'REDACTED',
            'CF-RAY' => 'REDACTED',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function defineSensitiveJsonParameters(): array
    {
        return [
            'password' => 'REDACTED',
            'username' => 'REDACTED',
            'hostname' => 'REDACTED',
            'access_key_id' => 'REDACTED',
            'access_key_secret' => 'REDACTED',
            'endpoint' => 'REDACTED',
            'key' => 'REDACTED',
            'secret' => 'REDACTED',
        ];
    }
}
