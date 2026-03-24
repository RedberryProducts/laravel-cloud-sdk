<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum SourceControlProvider: string
{
    case GITHUB = 'github';
    case GITLAB = 'gitlab';
    case BITBUCKET = 'bitbucket';
}
