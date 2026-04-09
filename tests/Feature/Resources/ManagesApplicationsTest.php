<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\CreateApplicationData;
use Redberry\LaravelCloudSdk\Data\Applications\UpdateApplicationData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\SourceControlProvider;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Applications\CreateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationAvatarRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\DeleteApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\GetApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\UpdateApplicationRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\UploadApplicationAvatarRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists applications', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/list'),
    ]);

    $result = (new LaravelCloud('token'))->applications();

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(ApplicationData::class);
    Saloon::assertSent(ListApplicationsRequest::class);
});

it('retrieves a single application by id', function () {
    Saloon::fake([
        GetApplicationRequest::class => new LaravelCloudFixture('applications/get'),
    ]);

    $result = (new LaravelCloud('token'))->application('app-a14fe54f-42b2-431c-9b3a-876900975139');

    Saloon::assertSent(GetApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
    expect($result->id)->toBe('app-a14fe54f-42b2-431c-9b3a-876900975139');
    expect($result->name)->toBe('updated-app');
    expect($result->region)->toBe(CloudRegion::UsEast1);
});

it('creates an application with named params', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createApplication(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: CloudRegion::UsEast1,
        sourceControlProviderType: SourceControlProvider::Github,
    );

    Saloon::assertSent(CreateApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
});

it('creates an application with a string region and provider type', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createApplication(
        repository: 'RedberryProducts/redberry-automations',
        name: 'test-app',
        region: 'us-east-1',
        sourceControlProviderType: 'github',
    );

    Saloon::assertSent(CreateApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
});

it('creates an application via createApplicationWith()', function () {
    Saloon::fake([
        CreateApplicationRequest::class => new LaravelCloudFixture('applications/create'),
    ]);

    $result = (new LaravelCloud('token'))->createApplicationWith(
        new CreateApplicationData(
            repository: 'RedberryProducts/redberry-automations',
            name: 'test-app',
            region: CloudRegion::UsEast1,
            sourceControlProviderType: SourceControlProvider::Github,
        )
    );

    Saloon::assertSent(CreateApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
});

it('updates an application with named params', function () {
    Saloon::fake([
        UpdateApplicationRequest::class => new LaravelCloudFixture('applications/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateApplication(
        'app-a14fe54f-42b2-431c-9b3a-876900975139',
        name: 'updated-app',
    );

    Saloon::assertSent(UpdateApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
    expect($result->name)->toBe('updated-app');
});

it('updates an application via updateApplicationWith()', function () {
    Saloon::fake([
        UpdateApplicationRequest::class => new LaravelCloudFixture('applications/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateApplicationWith(
        'app-a14fe54f-42b2-431c-9b3a-876900975139',
        new UpdateApplicationData(name: 'updated-app'),
    );

    Saloon::assertSent(UpdateApplicationRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);
});

it('deletes an application', function () {
    Saloon::fake([
        DeleteApplicationRequest::class => new LaravelCloudFixture('applications/delete'),
    ]);

    (new LaravelCloud('token'))->deleteApplication('app-a14fe54f-42b2-431c-9b3a-876900975139');

    Saloon::assertSent(DeleteApplicationRequest::class);
});

it('uploads an application avatar', function () {
    Saloon::fake([
        UploadApplicationAvatarRequest::class => new LaravelCloudFixture('applications/upload-avatar'),
    ]);

    $avatarPath = tempnam(sys_get_temp_dir(), 'avatar').'.png';
    $img = imagecreatetruecolor(100, 100);
    imagefill($img, 0, 0, imagecolorallocate($img, 66, 135, 245));
    imagepng($img, $avatarPath);
    imagedestroy($img);

    $result = (new LaravelCloud('token'))->uploadApplicationAvatar(
        'app-a14fe54f-42b2-431c-9b3a-876900975139',
        $avatarPath,
    );

    Saloon::assertSent(UploadApplicationAvatarRequest::class);
    expect($result)->toBeInstanceOf(ApplicationData::class);

    unlink($avatarPath);
});

it('deletes an application avatar', function () {
    Saloon::fake([
        DeleteApplicationAvatarRequest::class => new LaravelCloudFixture('applications/delete-avatar'),
    ]);

    (new LaravelCloud('token'))->deleteApplicationAvatar('app-a14fe54f-42b2-431c-9b3a-876900975139');

    Saloon::assertSent(DeleteApplicationAvatarRequest::class);
});
