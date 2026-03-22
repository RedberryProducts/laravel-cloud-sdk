<?php

use App\Data\LaravelCloud\Domains\UpdateDomainData;
use App\Enums\LaravelCloud\DomainVerificationMethod;

it('requires verificationMethod', function () {
    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::REAL_TIME);

    $array = $data->toArray();

    expect($array['verification_method'])->toBe('real_time');
});

it('serializes pre_verification correctly', function () {
    $data = new UpdateDomainData(verificationMethod: DomainVerificationMethod::PRE_VERIFICATION);

    expect($data->toArray()['verification_method'])->toBe('pre_verification');
});
