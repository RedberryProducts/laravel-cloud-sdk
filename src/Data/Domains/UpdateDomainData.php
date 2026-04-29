<?php

namespace Redberry\LaravelCloudSdk\Data\Domains;

use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class UpdateDomainData extends Data
{
    public function __construct(
        public string|DomainVerificationMethod $verificationMethod,
    ) {}
}
