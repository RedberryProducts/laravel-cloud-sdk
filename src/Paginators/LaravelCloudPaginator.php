<?php

namespace Redberry\LaravelCloudSdk\Paginators;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

class LaravelCloudPaginator extends PagedPaginator
{
    protected function isLastPage(Response $response): bool
    {
        $meta = $response->json('meta');

        return $meta['current_page'] >= $meta['last_page'];
    }

    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->dtoOrFail();
    }
}
