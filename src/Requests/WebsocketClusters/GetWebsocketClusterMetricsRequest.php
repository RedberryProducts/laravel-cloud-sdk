<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketClusterMetricsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $websocketServerId,
        private string|MetricPeriod|null $period = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->websocketServerId}/metrics";
    }

    public function defaultQuery(): array
    {
        if ($this->period === null) {
            return [];
        }

        return [
            'period' => $this->period instanceof MetricPeriod ? $this->period->value : $this->period,
        ];
    }

    public function createDtoFromResponse(Response $response): WebsocketClusterMetricsData
    {
        return WebsocketClusterMetricsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
