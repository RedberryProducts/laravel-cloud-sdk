<?php

use Redberry\LaravelCloudSdk\Data\Applications\ApplicationRepositoryData;

it('can be created from API response data', function () {
    $data = ApplicationRepositoryData::fromResponse([
        'full_name' => 'acme/my-app',
        'default_branch' => 'main',
    ]);

    expect($data)->toBeInstanceOf(ApplicationRepositoryData::class);
    expect($data->fullName)->toBe('acme/my-app');
    expect($data->defaultBranch)->toBe('main');
});
