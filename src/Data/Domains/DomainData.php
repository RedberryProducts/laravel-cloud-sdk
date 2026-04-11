<?php

namespace Redberry\LaravelCloudSdk\Data\Domains;

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentData;
use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainStatus;
use Redberry\LaravelCloudSdk\Enums\DomainType;
use Spatie\LaravelData\Data;

class DomainData extends Data
{
    /**
     * @param  array<string, DnsRecordData[]|null>  $dnsRecords
     */
    public function __construct(
        public string $id,
        public string $name,
        public string|DomainType $type,
        public string|DomainStatus $hostnameStatus,
        public string|DomainStatus $sslStatus,
        public string|DomainStatus $originStatus,
        public string|DomainRedirect|null $redirect,
        public string|DomainCloudflareStrategy|null $cloudflareStrategy,
        public ?bool $downtime,
        public bool $wildcardEnabled,
        public ?string $actionRequired,
        public array $dnsRecords,
        public ?CarbonImmutable $lastVerifiedAt,
        public ?CarbonImmutable $createdAt,
        public ?EnvironmentData $environment = null,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        $dnsRecords = [];
        foreach ($attributes['dns_records'] ?? [] as $key => $record) {
            if ($record === null) {
                $dnsRecords[$key] = null;
            } elseif (is_array($record) && (empty($record) || isset($record[0]))) {
                // Array of records (e.g. ssl can have multiple TXT entries, or be empty)
                $dnsRecords[$key] = array_map(
                    fn (array $r) => DnsRecordData::fromResponse($r),
                    $record
                );
            } else {
                // Single record object
                $dnsRecords[$key] = [DnsRecordData::fromResponse($record)];
            }
        }

        return new self(
            id: $id,
            name: $attributes['name'],
            type: DomainType::tryFrom($attributes['type']) ?? $attributes['type'],
            hostnameStatus: DomainStatus::tryFrom($attributes['hostname_status']) ?? $attributes['hostname_status'],
            sslStatus: DomainStatus::tryFrom($attributes['ssl_status']) ?? $attributes['ssl_status'],
            originStatus: DomainStatus::tryFrom($attributes['origin_status']) ?? $attributes['origin_status'],
            redirect: isset($attributes['redirect'])
                ? (DomainRedirect::tryFrom($attributes['redirect']) ?? $attributes['redirect'])
                : null,
            cloudflareStrategy: isset($attributes['cloudflare_strategy'])
                ? (DomainCloudflareStrategy::tryFrom($attributes['cloudflare_strategy']) ?? $attributes['cloudflare_strategy'])
                : null,
            downtime: $attributes['downtime'] ?? null,
            wildcardEnabled: $attributes['wildcard_enabled'] ?? false,
            actionRequired: $attributes['action_required'] ?? null,
            dnsRecords: $dnsRecords,
            lastVerifiedAt: isset($attributes['last_verified_at'])
                ? CarbonImmutable::parse($attributes['last_verified_at'])
                : null,
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
