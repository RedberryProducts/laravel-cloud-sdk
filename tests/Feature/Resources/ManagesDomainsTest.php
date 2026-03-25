<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Domains\CreateDomainData;
use Redberry\LaravelCloudSdk\Data\Domains\DomainData;
use Redberry\LaravelCloudSdk\Data\Domains\UpdateDomainData;
use Redberry\LaravelCloudSdk\Enums\DomainRedirect;
use Redberry\LaravelCloudSdk\Enums\DomainVerificationMethod;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Domains\CreateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\ListDomainsRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\UpdateDomainRequest;
use Redberry\LaravelCloudSdk\Requests\Domains\VerifyDomainRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists domains for an environment', function () {
    Saloon::fake([
        ListDomainsRequest::class => new LaravelCloudFixture('domains/list'),
    ]);

    $result = (new LaravelCloud('token'))->domains('env-a14fe550-4e39-4ff2-8016-a20e4d32a996');

    Saloon::assertSent(ListDomainsRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(DomainData::class);
});

it('creates a domain with named params', function () {
    Saloon::fake([
        CreateDomainRequest::class => new LaravelCloudFixture('domains/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDomain(
        environmentId: 'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        name: 'example.com',
        wwwRedirect: DomainRedirect::ROOT_TO_WWW,
        verificationMethod: DomainVerificationMethod::REAL_TIME,
    );

    Saloon::assertSent(CreateDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});

it('creates a domain with string enums', function () {
    Saloon::fake([
        CreateDomainRequest::class => new LaravelCloudFixture('domains/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDomain(
        environmentId: 'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        name: 'example.com',
        wwwRedirect: 'root_to_www',
        verificationMethod: 'real_time',
    );

    Saloon::assertSent(CreateDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});

it('creates a domain via createDomainWith()', function () {
    Saloon::fake([
        CreateDomainRequest::class => new LaravelCloudFixture('domains/create'),
    ]);

    $result = (new LaravelCloud('token'))->createDomainWith(
        'env-a14fe550-4e39-4ff2-8016-a20e4d32a996',
        new CreateDomainData(
            name: 'example.com',
            wwwRedirect: DomainRedirect::ROOT_TO_WWW,
            verificationMethod: DomainVerificationMethod::REAL_TIME,
        ),
    );

    Saloon::assertSent(CreateDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});

it('updates a domain with named params', function () {
    Saloon::fake([
        UpdateDomainRequest::class => new LaravelCloudFixture('domains/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateDomain(
        'domain-a15ddd8e-3829-4493-8229-2c2aae2872d4',
        verificationMethod: DomainVerificationMethod::PRE_VERIFICATION,
    );

    Saloon::assertSent(UpdateDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});

it('updates a domain via updateDomainWith()', function () {
    Saloon::fake([
        UpdateDomainRequest::class => new LaravelCloudFixture('domains/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateDomainWith(
        'domain-a15ddd8e-3829-4493-8229-2c2aae2872d4',
        new UpdateDomainData(verificationMethod: DomainVerificationMethod::PRE_VERIFICATION),
    );

    Saloon::assertSent(UpdateDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});

it('verifies a domain', function () {
    Saloon::fake([
        VerifyDomainRequest::class => new LaravelCloudFixture('domains/verify'),
    ]);

    $result = (new LaravelCloud('token'))->verifyDomain('domain-a15ddd8e-3829-4493-8229-2c2aae2872d4');

    Saloon::assertSent(VerifyDomainRequest::class);
    expect($result)->toBeInstanceOf(DomainData::class);
});
