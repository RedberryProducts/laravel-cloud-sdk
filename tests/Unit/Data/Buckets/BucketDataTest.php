<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketStatus;
use Redberry\LaravelCloudSdk\Enums\BucketType;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'my-bucket',
        'type' => 'cloudflare_r2',
        'status' => 'available',
        'visibility' => 'private',
        'jurisdiction' => 'default',
        'endpoint' => 'https://bucket.example.com',
        'url' => 'https://bucket.example.com/my-bucket',
        'allowed_origins' => ['https://example.com', 'https://app.example.com'],
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = BucketData::fromResponse($responseData, 'bucket-123');

    expect($data)->toBeInstanceOf(BucketData::class);
    expect($data->id)->toBe('bucket-123');
    expect($data->name)->toBe('my-bucket');
    expect($data->type)->toBe(BucketType::CLOUDFLARE_R2);
    expect($data->status)->toBe(BucketStatus::AVAILABLE);
    expect($data->visibility)->toBe(BucketVisibility::PRIVATE);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::DEFAULT);
    expect($data->endpoint)->toBe('https://bucket.example.com');
    expect($data->url)->toBe('https://bucket.example.com/my-bucket');
    expect($data->allowedOrigins)->toBe(['https://example.com', 'https://app.example.com']);
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null optional fields', function () {
    $responseData = [
        'name' => 'my-bucket',
        'type' => 'cloudflare_r2',
        'status' => 'creating',
        'visibility' => 'public',
        'jurisdiction' => 'eu',
        'endpoint' => null,
        'url' => null,
        'allowed_origins' => null,
    ];

    $data = BucketData::fromResponse($responseData, 'bucket-456');

    expect($data->endpoint)->toBeNull();
    expect($data->url)->toBeNull();
    expect($data->allowedOrigins)->toBeNull();
    expect($data->createdAt)->toBeNull();
    expect($data->visibility)->toBe(BucketVisibility::PUBLIC);
    expect($data->jurisdiction)->toBe(BucketJurisdiction::EU);
});
