<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketApplicationMetricsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $websocketApplicationId,
        private string|MetricPeriod|null $period = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-applications/{$this->websocketApplicationId}/metrics";
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

    public function createDtoFromResponse(Response $response): WebsocketApplicationMetricsData
    {
        return WebsocketApplicationMetricsData::fromResponse(
            $response->json('data'),
            $response->json('meta'),
        );
    }
}
