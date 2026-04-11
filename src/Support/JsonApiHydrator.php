<?php

namespace Redberry\LaravelCloudSdk\Support;

use Spatie\LaravelData\Data;

class JsonApiHydrator
{
    public static function hydrateOne(string $dtoClass, array $data, array $included = []): Data
    {
        return self::hydrate($dtoClass, $data, self::buildIncludedMap($included));
    }

    /**
     * @return Data[]
     */
    public static function hydrateMany(string $dtoClass, array $items, array $included = []): array
    {
        $map = self::buildIncludedMap($included);

        return array_map(fn (array $item) => self::hydrate($dtoClass, $item, $map), $items);
    }

    private static function hydrate(string $dtoClass, array $data, array $map): Data
    {
        $dto = $dtoClass::fromResponse($data['attributes'], $data['id']);

        foreach ($data['relationships'] ?? [] as $name => $relationship) {
            if (! array_key_exists('data', $relationship) || $relationship['data'] === null) {
                continue;
            }

            $property = JsonApiTypeRegistry::propertyName($dtoClass, $name);

            if (! property_exists($dto, $property)) {
                continue;
            }

            $linkage = $relationship['data'];

            $dto->{$property} = array_is_list($linkage)
                ? array_map(fn ($link) => self::resolve($link, $map), $linkage)
                : self::resolve($linkage, $map);
        }

        return $dto;
    }

    private static function resolve(array $link, array $map): Data
    {
        $item = $map["{$link['type']}:{$link['id']}"];
        $class = JsonApiTypeRegistry::dtoClass($link['type']);

        return $class::fromResponse($item['attributes'], $item['id']);
    }

    private static function buildIncludedMap(array $included): array
    {
        $map = [];

        foreach ($included as $item) {
            $map["{$item['type']}:{$item['id']}"] = $item;
        }

        return $map;
    }
}
