<?php

namespace Redberry\LaravelCloudSdk\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

class HtmlResponseException extends RequestException
{
    public function __construct(Response $response)
    {
        parent::__construct(
            $response,
            sprintf(
                'The API returned an HTML response for [%s %s]. The endpoint URL, path parameters, or request body are likely incorrect.',
                $response->getPendingRequest()->getMethod()->value,
                $response->getPendingRequest()->getUrl(),
            ),
        );
    }
}
