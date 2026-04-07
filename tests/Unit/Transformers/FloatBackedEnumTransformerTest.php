<?php

use Redberry\LaravelCloudSdk\Enums\NeonServerlessPostgresComputeUnit;
use Redberry\LaravelCloudSdk\Transformers\FloatBackedEnumTransformer;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;

it('converts a string-backed enum to a float', function () {
    $transformer = new FloatBackedEnumTransformer;

    $result = $transformer->transform(
        Mockery::mock(DataProperty::class),
        NeonServerlessPostgresComputeUnit::CU_0_25,
        Mockery::mock(TransformationContext::class),
    );

    expect($result)->toBe(0.25);
    expect($result)->toBeFloat();
});

it('converts all known compute unit enum values to their float equivalents', function (NeonServerlessPostgresComputeUnit $unit, float $expected) {
    $transformer = new FloatBackedEnumTransformer;
    $mock = Mockery::mock(DataProperty::class);
    $ctx = Mockery::mock(TransformationContext::class);

    expect($transformer->transform($mock, $unit, $ctx))->toBe($expected);
})->with([
    [NeonServerlessPostgresComputeUnit::CU_0_25, 0.25],
    [NeonServerlessPostgresComputeUnit::CU_0_5, 0.5],
    [NeonServerlessPostgresComputeUnit::CU_1, 1.0],
    [NeonServerlessPostgresComputeUnit::CU_2, 2.0],
    [NeonServerlessPostgresComputeUnit::CU_4, 4.0],
    [NeonServerlessPostgresComputeUnit::CU_8, 8.0],
    [NeonServerlessPostgresComputeUnit::CU_10, 10.0],
]);

it('passes through non-enum float values unchanged', function () {
    $transformer = new FloatBackedEnumTransformer;

    $result = $transformer->transform(
        Mockery::mock(DataProperty::class),
        3.5,
        Mockery::mock(TransformationContext::class),
    );

    expect($result)->toBe(3.5);
});

it('passes through null unchanged', function () {
    $transformer = new FloatBackedEnumTransformer;

    $result = $transformer->transform(
        Mockery::mock(DataProperty::class),
        null,
        Mockery::mock(TransformationContext::class),
    );

    expect($result)->toBeNull();
});
