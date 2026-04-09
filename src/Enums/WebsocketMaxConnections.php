<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum WebsocketMaxConnections: int
{
    case Connections100 = 100;
    case Connections200 = 200;
    case Connections500 = 500;
    case Connections2000 = 2000;
    case Connections5000 = 5000;
    case Connections10000 = 10000;
}
