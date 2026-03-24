<?php

namespace Redberry\LaravelCloudSdk\Tests\Fixtures;

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
        $body = $this->redactDomainData($body);

        $recordedResponse->data = json_encode($body, JSON_THROW_ON_ERROR);

        return $recordedResponse;
    }

    private function redactDomainData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (! is_array($value)) {
                continue;
            }

            if (($value['type'] ?? null) === 'domains' && isset($value['attributes'])) {
                $value['attributes']['name'] = 'REDACTED';
                $value['attributes'] = $this->redactDomainAttributes($value['attributes']);
            } else {
                $value = $this->redactDomainData($value);
            }
        }

        return $data;
    }

    private function redactDomainAttributes(array $attributes): array
    {
        if (isset($attributes['dns_records'])) {
            $attributes['dns_records'] = $this->redactDnsRecords($attributes['dns_records']);
        }

        foreach (['www', 'wildcard'] as $sub) {
            if (isset($attributes[$sub]['dns_records'])) {
                $attributes[$sub]['dns_records'] = $this->redactDnsRecords($attributes[$sub]['dns_records']);
            }
        }

        return $attributes;
    }

    private function redactDnsRecords(array $dnsRecords): array
    {
        foreach ($dnsRecords as &$record) {
            if ($record === null || ! is_array($record)) {
                continue;
            }

            if (isset($record[0]) || empty($record)) {
                // Indexed array of record objects (e.g. ssl)
                foreach ($record as &$entry) {
                    if (is_array($entry)) {
                        $entry = $this->redactDnsRecordFields($entry);
                    }
                }
            } else {
                // Single record object (e.g. origin, pre_verification)
                $record = $this->redactDnsRecordFields($record);
            }
        }

        return $dnsRecords;
    }

    private function redactDnsRecordFields(array $record): array
    {
        if (! empty($record['name'])) {
            $record['name'] = 'REDACTED';
        }

        if (! empty($record['value'])) {
            $record['value'] = 'REDACTED';
        }

        return $record;
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
