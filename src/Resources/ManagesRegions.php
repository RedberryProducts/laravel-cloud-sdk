<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Requests\Meta\ListRegionsRequest;

trait ManagesRegions
{
    public function regions(): Collection
    {
        return $this->connector->send(new ListRegionsRequest)->dtoOrFail();
    }
}
