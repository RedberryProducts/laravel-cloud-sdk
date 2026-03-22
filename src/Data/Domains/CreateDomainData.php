<?php

namespace App\Data\LaravelCloud\Domains;

use App\Enums\LaravelCloud\DomainCloudflareStrategy;
use App\Enums\LaravelCloud\DomainRedirect;
use App\Enums\LaravelCloud\DomainVerificationMethod;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class CreateDomainData extends Data
{
    public function __construct(
        public string $name,
        public DomainRedirect $wwwRedirect,
        public DomainVerificationMethod $verificationMethod,
        public DomainCloudflareStrategy|Optional $cloudflareStrategy = new Optional,
        public bool|null|Optional $wildcardEnabled = new Optional,
        public bool|null|Optional $allowDowntime = new Optional,
    ) {}
}
