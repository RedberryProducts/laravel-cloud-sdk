<?php

namespace Redberry\LaravelCloudSdk\Data\Domains;

use Redberry\LaravelCloudSdk\Enums\DomainCloudflareStrategy;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateDomainData extends Data
{
    public function __construct(
        public string $name,
        public string|DomainRedirect $wwwRedirect,
        public string|DomainVerificationMethod $verificationMethod,
        public string|DomainCloudflareStrategy|Optional $cloudflareStrategy = new Optional,
        public bool|null|Optional $wildcardEnabled = new Optional,
        public bool|null|Optional $allowDowntime = new Optional,
    ) {}
}
