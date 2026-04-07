<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Requests\Meta\ListIpAddressesRequest;

trait ManagesIpAddresses
{
    /**
     * @return Collection<string, \Redberry\LaravelCloudSdk\Data\Meta\IpAddressData>
     */
    public function ipAddresses(string|CloudRegion|null $region = null): Collection
    {
        return $this->connector->send(new ListIpAddressesRequest($region))->dtoOrFail();
    }
}
