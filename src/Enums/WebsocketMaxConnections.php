<?php

namespace App\Enums\LaravelCloud;

enum WebsocketMaxConnections: int
{
    case CONNECTIONS_100 = 100;
    case CONNECTIONS_200 = 200;
    case CONNECTIONS_500 = 500;
    case CONNECTIONS_2000 = 2000;
    case CONNECTIONS_5000 = 5000;
    case CONNECTIONS_10000 = 10000;
}
