<?php

namespace Redberry\LaravelCloudSdk\Data\Environments;

use Redberry\LaravelCloudSdk\Enums\CacheStrategy;
use Redberry\LaravelCloudSdk\Enums\EnvironmentColor;
use Redberry\LaravelCloudSdk\Enums\FirewallRateLimitLevel;
use Redberry\LaravelCloudSdk\Enums\NodeVersion;
use Redberry\LaravelCloudSdk\Enums\PhpVersion;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersContentType;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersFrame;
use Redberry\LaravelCloudSdk\Enums\ResponseHeadersRobotsTag;
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
        public EnvironmentColor|Optional $color = new Optional,
        public string|Optional $branch = new Optional,
        public PhpVersion|Optional $phpVersion = new Optional,
        public NodeVersion|Optional $nodeVersion = new Optional,
        public string|null|Optional $buildCommand = new Optional,
        public string|null|Optional $deployCommand = new Optional,
        public bool|Optional $usesPushToDeploy = new Optional,
        public bool|Optional $usesDeployHook = new Optional,
        public bool|Optional $usesOctane = new Optional,
        public bool|Optional $usesVanityDomain = new Optional,
        public int|Optional $timeout = new Optional,
        public int|Optional $sleepTimeout = new Optional,
        public int|Optional $shutdownTimeout = new Optional,
        public bool|Optional $usesPurgeEdgeCacheOnDeploy = new Optional,
        public string|null|Optional $nightwatchToken = new Optional,
        public CacheStrategy|Optional $cacheStrategy = new Optional,
        public ResponseHeadersFrame|Optional $responseHeadersFrame = new Optional,
        public ResponseHeadersContentType|Optional $responseHeadersContentType = new Optional,
        public ResponseHeadersRobotsTag|Optional $responseHeadersRobotsTag = new Optional,
        public HstsData|null|Optional $responseHeadersHsts = new Optional,
        /** @var FilesystemKeyData[]|null */
        public array|null|Optional $filesystemKeys = new Optional,
        public FirewallRateLimitLevel|null|Optional $firewallRateLimitLevel = new Optional,
        public bool|Optional $firewallUnderAttackMode = new Optional,
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
