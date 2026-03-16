<?php

use App\Data\LaravelCloud\Applications\CreateApplicationData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\VcsProviderEnum;
use Spatie\LaravelData\Optional;

it('can be constructed with required parameters', function () {
    $data = new CreateApplicationData(
        repository: 'acme/my-app',
        name: 'my-app',
        region: CloudRegion::US_EAST_1,
        sourceControlProviderType: VcsProviderEnum::GITHUB,
    );

    expect($data->repository)->toBe('acme/my-app');
    expect($data->name)->toBe('my-app');
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->sourceControlProviderType)->toBe(VcsProviderEnum::GITHUB);
    expect($data->clusterId)->toBeInstanceOf(Optional::class);
});

it('serializes fields as snake_case', function () {
    $data = new CreateApplicationData(
        repository: 'acme/my-app',
        name: 'my-app',
        region: CloudRegion::EU_CENTRAL_1,
        sourceControlProviderType: VcsProviderEnum::GITLAB,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('source_control_provider_type');
    expect($array)->not->toHaveKey('sourceControlProviderType');
    expect($array['source_control_provider_type'])->toBe('gitlab');
    expect($array['region'])->toBe('eu-central-1');
});
