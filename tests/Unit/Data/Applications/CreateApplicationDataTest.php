<?php

use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Spatie\LaravelData\Optional;

it('can be constructed with required parameters', function () {
    $data = new CreateApplicationData(
        repository: 'acme/my-app',
        name: 'my-app',
        region: CloudRegion::UsEast1,
        sourceControlProviderType: SourceControlProvider::Github,
    );

    expect($data->repository)->toBe('acme/my-app');
    expect($data->name)->toBe('my-app');
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->sourceControlProviderType)->toBe(SourceControlProvider::Github);
    expect($data->clusterId)->toBeInstanceOf(Optional::class);
});

it('serializes fields as snake_case', function () {
    $data = new CreateApplicationData(
        repository: 'acme/my-app',
        name: 'my-app',
        region: CloudRegion::EuCentral1,
        sourceControlProviderType: SourceControlProvider::Gitlab,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('source_control_provider_type');
    expect($array)->not->toHaveKey('sourceControlProviderType');
    expect($array['source_control_provider_type'])->toBe('gitlab');
    expect($array['region'])->toBe('eu-central-1');
});
