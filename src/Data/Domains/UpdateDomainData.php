<?php

namespace Redberry\LaravelCloudSdk\Data\Domains;

use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateDomainData extends Data
{
    public function __construct(
        public string|DomainVerificationMethod $verificationMethod,
    ) {}
}
