<?php

use Redberry\LaravelCloudSdk\Data\Branches\BranchData;

it('can be constructed from response', function () {
    $data = BranchData::fromResponse(
        attributes: ['name' => 'main'],
        id: 'branch_123',
    );

    expect($data)
        ->id->toBe('branch_123')
        ->name->toBe('main');
});
