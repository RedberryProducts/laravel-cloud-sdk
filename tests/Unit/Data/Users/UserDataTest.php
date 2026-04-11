<?php

use Redberry\LaravelCloudSdk\Data\Users\UserData;

it('can be constructed from response', function () {
    $data = UserData::fromResponse(
        attributes: ['name' => 'John Doe'],
        id: 'user_123',
    );

    expect($data)
        ->id->toBe('user_123')
        ->name->toBe('John Doe');
});
