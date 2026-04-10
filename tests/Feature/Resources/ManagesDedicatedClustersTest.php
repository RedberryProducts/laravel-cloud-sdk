<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\DedicatedClusters\ListDedicatedClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists dedicated clusters', function () {
    Saloon::fake([
        ListDedicatedClustersRequest::class => new LaravelCloudFixture('dedicated-clusters/list'),
    ]);

    $result = (new LaravelCloud('token'))->dedicatedClusters();

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->all())->toBeArray();
    Saloon::assertSent(ListDedicatedClustersRequest::class);
});
