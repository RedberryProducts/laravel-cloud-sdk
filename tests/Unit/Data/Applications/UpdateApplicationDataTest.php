<?php

use Redberry\LaravelCloudSdk\Data\Applications\UpdateApplicationData;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Spatie\LaravelData\Optional;

it('can be constructed with partial parameters', function () {
    $data = new UpdateApplicationData(name: 'new-name');

    expect($data->name)->toBe('new-name');
    expect($data->slug)->toBeInstanceOf(Optional::class);
    expect($data->sourceControlProviderType)->toBeInstanceOf(Optional::class);
    expect($data->defaultEnvironmentId)->toBeInstanceOf(Optional::class);
    expect($data->repository)->toBeInstanceOf(Optional::class);
    expect($data->slackChannel)->toBeInstanceOf(Optional::class);
});

it('defaults all parameters to Optional', function () {
    $data = new UpdateApplicationData;

    expect($data->sourceControlProviderType)->toBeInstanceOf(Optional::class);
    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->slug)->toBeInstanceOf(Optional::class);
    expect($data->defaultEnvironmentId)->toBeInstanceOf(Optional::class);
    expect($data->repository)->toBeInstanceOf(Optional::class);
    expect($data->slackChannel)->toBeInstanceOf(Optional::class);
});

it('serializes fields as snake_case', function () {
    $data = new UpdateApplicationData(
        sourceControlProviderType: SourceControlProvider::GITHUB,
        defaultEnvironmentId: 'env-123',
        slackChannel: '#deploys',
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('source_control_provider_type');
    expect($array)->toHaveKey('default_environment_id');
    expect($array)->toHaveKey('slack_channel');
    expect($array)->not->toHaveKey('sourceControlProviderType');
    expect($array)->not->toHaveKey('defaultEnvironmentId');
    expect($array)->not->toHaveKey('slackChannel');
    expect($array['source_control_provider_type'])->toBe('github');
    expect($array['default_environment_id'])->toBe('env-123');
    expect($array['slack_channel'])->toBe('#deploys');
});
