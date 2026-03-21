<?php

namespace Tests\Fixtures\LaravelCloud;

use Saloon\Data\RecordedResponse;
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
            'secret' => 'REDACTED',
        ];
    }

    /**
     * Extends standard JSON redaction with context-aware rules that
     * defineSensitiveJsonParameters() cannot express (e.g. only redacting
     * `value` when nested inside `environment_variables`).
     *
     * @throws \JsonException
     */
    protected function swapSensitiveJson(RecordedResponse $recordedResponse): RecordedResponse
    {
        $recordedResponse = parent::swapSensitiveJson($recordedResponse);

        $body = json_decode($recordedResponse->data, true);

        if (empty($body) || json_last_error() !== JSON_ERROR_NONE) {
            return $recordedResponse;
        }

        $body = $this->redactEnvironmentVariableValues($body);
        $body = $this->redactWebsocketApplicationKeys($body);

        $recordedResponse->data = json_encode($body, JSON_THROW_ON_ERROR);

        return $recordedResponse;
    }

    private function redactWebsocketApplicationKeys(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                if (($value['type'] ?? null) === 'websocketApplications' && isset($value['attributes']['key'])) {
                    $value['attributes']['key'] = 'REDACTED';
                } else {
                    $value = $this->redactWebsocketApplicationKeys($value);
                }
            }
        }

        return $data;
    }

    private function redactEnvironmentVariableValues(array $data): array
    {
        foreach ($data as $key => &$value) {
            if ($key === 'environment_variables' && is_array($value)) {
                foreach ($value as &$variable) {
                    if (is_array($variable) && array_key_exists('value', $variable)) {
                        $variable['value'] = 'REDACTED';
                    }
                }
            } elseif (is_array($value)) {
                $value = $this->redactEnvironmentVariableValues($value);
            }
        }

        return $data;
    }
}
