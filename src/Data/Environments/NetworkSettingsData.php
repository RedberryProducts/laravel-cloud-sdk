<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Spatie\LaravelData\Data;

class NetworkSettingsData extends Data
{
    public function __construct(
        public string $cacheStrategy,
        public string $responseHeadersFrame,
        public string $responseHeadersContentType,
        public string $responseHeadersRobotsTag,
        public HstsData $responseHeadersHsts,
        public ?string $firewallRateLimitLevel,
        public bool $firewallUnderAttackMode,
    ) {}

    public static function fromResponse(array $attributes): self
    {
        return new self(
            cacheStrategy: $attributes['cache']['strategy'],
            responseHeadersFrame: $attributes['response_headers']['frame'],
            responseHeadersContentType: $attributes['response_headers']['content_type'],
            responseHeadersRobotsTag: $attributes['response_headers']['robots_tag'],
            responseHeadersHsts: HstsData::fromResponse($attributes['response_headers']['hsts']),
            firewallRateLimitLevel: $attributes['firewall']['rate_limit']['level'] ?? null,
            firewallUnderAttackMode: isset($attributes['firewall']['under_attack_mode_started_at']),
        );
    }
}
