<?php

use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Meta\GetOrganizationRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('retrieves the organization', function () {
    Saloon::fake([
        GetOrganizationRequest::class => new LaravelCloudFixture('meta/organization'),
    ]);

    $result = (new LaravelCloud('token'))->organization();

    expect($result)->toBeInstanceOf(OrganizationData::class);
    expect($result->id)->toBeString();
    expect($result->name)->toBeString();
    expect($result->slug)->toBeString();
    Saloon::assertSent(GetOrganizationRequest::class);
});
