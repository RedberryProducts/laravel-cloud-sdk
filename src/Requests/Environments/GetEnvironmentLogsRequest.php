<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentLogEntryData;
use Redberry\LaravelCloudSdk\Enums\LogFilterType;
use Redberry\LaravelCloudSdk\Paginators\LaravelCloudCursorPaginator;
use Saloon\Enums\Method;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasRequestPagination;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\PaginationPlugin\Paginator;

class GetEnvironmentLogsRequest extends Request implements HasRequestPagination, Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $environmentId,
        private string $from,
        private string $to,
        private ?string $searchQuery = null,
        private string|LogFilterType|null $type = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}/logs";
    }

    public function defaultQuery(): array
    {
        $query = [
            'from' => $this->from,
            'to' => $this->to,
        ];

        if ($this->searchQuery !== null) {
            $query['query'] = $this->searchQuery;
        }

        if ($this->type !== null) {
            $query['type'] = $this->type instanceof LogFilterType ? $this->type->value : $this->type;
        }

        return $query;
    }

    /**
     * @return EnvironmentLogEntryData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $entry) => EnvironmentLogEntryData::fromResponse($entry),
            $response->json('data'),
        );
    }

    public function paginate(Connector $connector): Paginator
    {
        return new LaravelCloudCursorPaginator(connector: $connector, request: $this);
    }
}
