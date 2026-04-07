<?php

namespace Redberry\LaravelCloudSdk\Resources;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\Meta\IpAddressData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Requests\Meta\ListIpAddressesRequest;

trait ManagesIpAddresses
{
    /**
     * @return Collection<string, IpAddressData>
     */
    public function ipAddresses(string|CloudRegion|null $region = null): Collection
    {
        return $this->connector->send(new ListIpAddressesRequest($region))->dtoOrFail();
    }
}
