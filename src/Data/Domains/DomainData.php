<?php

namespace App\Data\LaravelCloud\Domains;

use App\Enums\LaravelCloud\DomainCloudflareStrategy;
use App\Enums\LaravelCloud\DomainRedirect;
use App\Enums\LaravelCloud\DomainStatus;
use App\Enums\LaravelCloud\DomainType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class DomainData extends Data
{
    /**
     * @param  array<string, DnsRecordData[]|null>  $dnsRecords
     */
    public function __construct(
        public string $id,
        public string $name,
        public DomainType $type,
        public DomainStatus $hostnameStatus,
        public DomainStatus $sslStatus,
        public DomainStatus $originStatus,
        public ?DomainRedirect $redirect,
        public ?DomainCloudflareStrategy $cloudflareStrategy,
        public ?bool $downtime,
        public bool $wildcardEnabled,
        public ?string $actionRequired,
        public array $dnsRecords,
        public ?CarbonImmutable $lastVerifiedAt,
        public ?CarbonImmutable $createdAt,
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
            type: DomainType::from($attributes['type']),
            hostnameStatus: DomainStatus::from($attributes['hostname_status']),
            sslStatus: DomainStatus::from($attributes['ssl_status']),
            originStatus: DomainStatus::from($attributes['origin_status']),
            redirect: isset($attributes['redirect'])
                ? DomainRedirect::from($attributes['redirect'])
                : null,
            cloudflareStrategy: isset($attributes['cloudflare_strategy'])
                ? DomainCloudflareStrategy::from($attributes['cloudflare_strategy'])
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
