<?php

namespace App\Data\LaravelCloud\Environments;

use App\Enums\LaravelCloud\NodeVersion;
use App\Enums\LaravelCloud\PhpVersion;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateEnvironmentData extends Data
{
    public function __construct(
        public string|Optional $name = new Optional,
        public string|Optional $slug = new Optional,
        public string|Optional $branch = new Optional,
        public PhpVersion|Optional $phpVersion = new Optional,
        public NodeVersion|Optional $nodeVersion = new Optional,
        public string|null|Optional $buildCommand = new Optional,
        public string|null|Optional $deployCommand = new Optional,
        public bool|Optional $usesPushToDeploy = new Optional,
        public bool|Optional $usesDeployHook = new Optional,
        public bool|Optional $usesOctane = new Optional,
        public string|null|Optional $databaseSchemaId = new Optional,
        public string|null|Optional $cacheId = new Optional,
        public string|null|Optional $websocketApplicationId = new Optional,
    ) {}

    public function toArray(): array
    {
        $array = parent::toArray();

        if ($this->phpVersion instanceof PhpVersion) {
            $array['php_version'] = $this->phpVersion->value.':1';
        }

        return $array;
    }
}
