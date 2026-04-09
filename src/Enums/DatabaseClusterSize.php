<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DatabaseClusterSize: string
{
    // Laravel MySQL (db-flex)
    case Flex1vcpu512mb = 'db-flex.m-1vcpu-512mb';
    case Flex1vcpu1gb = 'db-flex.m-1vcpu-1gb';
    case Flex1vcpu2gb = 'db-flex.m-1vcpu-2gb';
    case Flex1vcpu4gb = 'db-flex.m-1vcpu-4gb';

    // Laravel MySQL (db-pro)
    case Pro1vcpu4gb = 'db-pro.m-1vcpu-4gb';
    case Pro2vcpu8gb = 'db-pro.m-2vcpu-8gb';
    case Pro4vcpu16gb = 'db-pro.m-4vcpu-16gb';
    case Pro8vcpu32gb = 'db-pro.m-8vcpu-32gb';

    // AWS RDS (db.m8g)
    case M8gLarge = 'db.m8g.large';
    case M8gXlarge = 'db.m8g.xlarge';
    case M8g2xlarge = 'db.m8g.2xlarge';
    case M8g4xlarge = 'db.m8g.4xlarge';
    case M8g8xlarge = 'db.m8g.8xlarge';
    case M8g12xlarge = 'db.m8g.12xlarge';
    case M8g16xlarge = 'db.m8g.16xlarge';
    case M8g24xlarge = 'db.m8g.24xlarge';
    case M8g48xlarge = 'db.m8g.48xlarge';

    // AWS RDS (db.m7g)
    case M7gLarge = 'db.m7g.large';
    case M7gXlarge = 'db.m7g.xlarge';
    case M7g2xlarge = 'db.m7g.2xlarge';
    case M7g4xlarge = 'db.m7g.4xlarge';
    case M7g8xlarge = 'db.m7g.8xlarge';
    case M7g12xlarge = 'db.m7g.12xlarge';
    case M7g16xlarge = 'db.m7g.16xlarge';

    // AWS RDS (db.t4g)
    case T4gMicro = 'db.t4g.micro';
    case T4gSmall = 'db.t4g.small';
    case T4gMedium = 'db.t4g.medium';
    case T4gLarge = 'db.t4g.large';
    case T4gXlarge = 'db.t4g.xlarge';
    case T4g2xlarge = 'db.t4g.2xlarge';
}
