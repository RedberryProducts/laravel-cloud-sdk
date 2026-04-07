<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseClusterSize: string
{
    // Laravel MySQL (db-flex)
    case FLEX_1VCPU_512MB = 'db-flex.m-1vcpu-512mb';
    case FLEX_1VCPU_1GB = 'db-flex.m-1vcpu-1gb';
    case FLEX_1VCPU_2GB = 'db-flex.m-1vcpu-2gb';
    case FLEX_1VCPU_4GB = 'db-flex.m-1vcpu-4gb';

    // Laravel MySQL (db-pro)
    case PRO_1VCPU_4GB = 'db-pro.m-1vcpu-4gb';
    case PRO_2VCPU_8GB = 'db-pro.m-2vcpu-8gb';
    case PRO_4VCPU_16GB = 'db-pro.m-4vcpu-16gb';
    case PRO_8VCPU_32GB = 'db-pro.m-8vcpu-32gb';

    // AWS RDS (db.m8g)
    case M8G_LARGE = 'db.m8g.large';
    case M8G_XLARGE = 'db.m8g.xlarge';
    case M8G_2XLARGE = 'db.m8g.2xlarge';
    case M8G_4XLARGE = 'db.m8g.4xlarge';
    case M8G_8XLARGE = 'db.m8g.8xlarge';
    case M8G_12XLARGE = 'db.m8g.12xlarge';
    case M8G_16XLARGE = 'db.m8g.16xlarge';
    case M8G_24XLARGE = 'db.m8g.24xlarge';
    case M8G_48XLARGE = 'db.m8g.48xlarge';

    // AWS RDS (db.m7g)
    case M7G_LARGE = 'db.m7g.large';
    case M7G_XLARGE = 'db.m7g.xlarge';
    case M7G_2XLARGE = 'db.m7g.2xlarge';
    case M7G_4XLARGE = 'db.m7g.4xlarge';
    case M7G_8XLARGE = 'db.m7g.8xlarge';
    case M7G_12XLARGE = 'db.m7g.12xlarge';
    case M7G_16XLARGE = 'db.m7g.16xlarge';

    // AWS RDS (db.t4g)
    case T4G_MICRO = 'db.t4g.micro';
    case T4G_SMALL = 'db.t4g.small';
    case T4G_MEDIUM = 'db.t4g.medium';
    case T4G_LARGE = 'db.t4g.large';
    case T4G_XLARGE = 'db.t4g.xlarge';
    case T4G_2XLARGE = 'db.t4g.2xlarge';
}
