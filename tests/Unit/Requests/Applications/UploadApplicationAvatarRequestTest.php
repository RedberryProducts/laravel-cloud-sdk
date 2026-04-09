<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Applications\ApplicationData;
use Redberry\LaravelCloudSdk\Requests\Applications\ListApplicationsRequest;
use Redberry\LaravelCloudSdk\Requests\Applications\UploadApplicationAvatarRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new UploadApplicationAvatarRequest('app-123', '/tmp/avatar.png');

    expect($request->resolveEndpoint())->toBe('/applications/app-123/avatar');
});

it('has the correct HTTP method', function () {
    $request = new UploadApplicationAvatarRequest('app-123', '/tmp/avatar.png');

    expect($request->getMethod())->toBe(Method::POST);
});

it('implements HasBody', function () {
    $request = new UploadApplicationAvatarRequest('app-123', '/tmp/avatar.png');

    expect($request)->toBeInstanceOf(HasBody::class);
});

it('uploads an avatar and returns ApplicationData', function () {
    Saloon::fake([
        ListApplicationsRequest::class => new LaravelCloudFixture('applications/avatar-list'),
        UploadApplicationAvatarRequest::class => new LaravelCloudFixture('applications/upload-avatar'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstApp = $connector->send(new ListApplicationsRequest)->dtoOrFail()[0];

    $avatarPath = tempnam(sys_get_temp_dir(), 'avatar').'.png';
    $img = imagecreatetruecolor(100, 100);
    imagefill($img, 0, 0, imagecolorallocate($img, 66, 135, 245));
    imagepng($img, $avatarPath);
    imagedestroy($img);

    $response = $connector->send(new UploadApplicationAvatarRequest($firstApp->id, $avatarPath));

    Saloon::assertSent(UploadApplicationAvatarRequest::class);

    $result = $response->dtoOrFail();
    expect($result)->toBeInstanceOf(ApplicationData::class);

    unlink($avatarPath);
});
