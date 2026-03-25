<?php

namespace Redberry\LaravelCloudSdk;

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Resources\ManagesApplications;
use Redberry\LaravelCloudSdk\Resources\ManagesBucketKeys;
use Redberry\LaravelCloudSdk\Resources\ManagesBuckets;
use Redberry\LaravelCloudSdk\Resources\ManagesCaches;
use Redberry\LaravelCloudSdk\Resources\ManagesDatabaseClusters;
use Redberry\LaravelCloudSdk\Resources\ManagesDatabases;
use Redberry\LaravelCloudSdk\Resources\ManagesDomains;
use Redberry\LaravelCloudSdk\Resources\ManagesEnvironments;
use Redberry\LaravelCloudSdk\Resources\ManagesInstances;
use Redberry\LaravelCloudSdk\Resources\ManagesRegions;
use Redberry\LaravelCloudSdk\Resources\ManagesWebsocketApplications;
use Redberry\LaravelCloudSdk\Resources\ManagesWebsocketClusters;

class LaravelCloud
{
    use ManagesApplications;
    use ManagesBucketKeys;
    use ManagesBuckets;
    use ManagesCaches;
    use ManagesDatabaseClusters;
    use ManagesDatabases;
    use ManagesDomains;
    use ManagesEnvironments;
    use ManagesInstances;
    use ManagesRegions;
    use ManagesWebsocketApplications;
    use ManagesWebsocketClusters;

    protected LaravelCloudConnector $connector;

    public function __construct(string $token)
    {
        $this->connector = new LaravelCloudConnector($token);
    }

}
