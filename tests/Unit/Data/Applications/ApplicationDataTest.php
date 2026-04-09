<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationRepositoryData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;

it('can be created from API response data', function () {
    $data = ApplicationData::fromResponse([
        'name' => 'my-app',
        'slug' => 'my-app',
        'region' => 'us-east-1',
        'slack_channel' => '#deployments',
        'avatar_url' => 'https://example.com/avatar.png',
        'repository' => [
            'full_name' => 'acme/my-app',
            'default_branch' => 'main',
        ],
        'created_at' => '2024-06-15T10:30:00Z',
    ], 'app-123');

    expect($data)->toBeInstanceOf(ApplicationData::class);
    expect($data->id)->toBe('app-123');
    expect($data->name)->toBe('my-app');
    expect($data->slug)->toBe('my-app');
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->slackChannel)->toBe('#deployments');
    expect($data->avatarUrl)->toBe('https://example.com/avatar.png');
    expect($data->repository)->toBeInstanceOf(ApplicationRepositoryData::class);
    expect($data->repository->fullName)->toBe('acme/my-app');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $data = ApplicationData::fromResponse([
        'name' => 'my-app',
        'slug' => 'my-app',
        'region' => 'eu-central-1',
        'slack_channel' => null,
        'avatar_url' => null,
        'repository' => null,
        'created_at' => null,
    ], 'app-456');

    expect($data->slackChannel)->toBeNull();
    expect($data->avatarUrl)->toBeNull();
    expect($data->repository)->toBeNull();
    expect($data->createdAt)->toBeNull();
});
