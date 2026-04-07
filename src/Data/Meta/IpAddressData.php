<?php

namespace Redberry\LaravelCloudSdk\Data\Meta;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Spatie\LaravelData\Data;

class IpAddressData extends Data
{
    public function __construct(
        public string|CloudRegion $region,
        public array $ipv4,
        public array $ipv6,
    ) {}

    public static function fromResponse(string $region, array $attributes): self
    {
        return new self(
            region: CloudRegion::tryFrom($region) ?? $region,
            ipv4: $attributes['ipv4'],
            ipv6: $attributes['ipv6'],
        );
    }
}
