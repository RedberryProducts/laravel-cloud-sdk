<?php

namespace App\Data\LaravelCloud\Domains;

use App\Enums\LaravelCloud\DomainVerificationMethod;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateDomainData extends Data
{
    public function __construct(
        public DomainVerificationMethod $verificationMethod,
    ) {}
}
