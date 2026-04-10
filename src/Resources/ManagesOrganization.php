<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Redberry\LaravelCloudSdk\Data\Meta\OrganizationData;
use Redberry\LaravelCloudSdk\Requests\Meta\GetOrganizationRequest;

trait ManagesOrganization
{
    public function organization(): OrganizationData
    {
        return $this->connector->send(new GetOrganizationRequest)->dtoOrFail();
    }
}
