<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Meta\RegionData;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Meta\ListRegionsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists available regions', function () {
    Saloon::fake([
        ListRegionsRequest::class => new LaravelCloudFixture('meta/list-regions'),
    ]);

    $result = (new LaravelCloud('token'))->regions();

    Saloon::assertSent(ListRegionsRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(RegionData::class);
});
