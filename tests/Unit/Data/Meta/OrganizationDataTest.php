<?php

use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'My Organization',
        'slug' => 'my-organization',
    ];

    $data = OrganizationData::fromResponse($responseData, 'org-123');

    expect($data)->toBeInstanceOf(OrganizationData::class);
    expect($data->id)->toBe('org-123');
    expect($data->name)->toBe('My Organization');
    expect($data->slug)->toBe('my-organization');
});
