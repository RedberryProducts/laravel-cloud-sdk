<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CommandStatus: string
{
    case Pending = 'pending';
    case Created = 'command.created';
    case Running = 'command.running';
    case Failure = 'command.failure';
    case Success = 'command.success';
}
