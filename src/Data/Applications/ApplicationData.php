<?php

namespace Redberry\LaravelCloudSdk\Data\Applications;

use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class ApplicationData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public CloudRegion $region,
        public ?string $slackChannel,
        public ?string $avatarUrl,
        public ?ApplicationRepositoryData $repository,
        public ?CarbonImmutable $createdAt,
    ) {}

    public static function fromResponse(array $attributes, string $id): self
    {
        return new self(
            id: $id,
            name: $attributes['name'],
            slug: $attributes['slug'],
            region: CloudRegion::from($attributes['region']),
            slackChannel: $attributes['slack_channel'],
            avatarUrl: $attributes['avatar_url'],
            repository: isset($attributes['repository'])
                ? ApplicationRepositoryData::fromResponse($attributes['repository'])
                : null,
            createdAt: isset($attributes['created_at'])
                ? CarbonImmutable::parse($attributes['created_at'])
                : null,
        );
    }
}
