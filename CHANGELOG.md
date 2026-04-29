# Changelog

All notable changes to `laravel-cloud-sdk` will be documented in this file.

## 1.1.0 - 2026-04-29

- Input-side Data classes now round-trip through `Data::from($dto->toArray())`. Replaced the per-class `MapOutputName(SnakeCaseMapper)` attribute with `MapName(SnakeCaseMapper)` so snake_case payloads (e.g. when casting an Eloquent JSON column) reconstruct the original DTO.
- `CreateDatabaseClusterData` and `DatabaseClusterData` now resolve their `config` union from the `type` discriminator via the new `ResolvesDatabaseClusterConfig` trait. Unknown database types fall back to the raw array instead of throwing, matching the SDK's existing soft-typing pattern for `string|EnumClass` unions. The `config` property type has been widened to include `array`.
- `CreateDatabaseClusterData` exposes a `fromArray` magical creation method so Spatie's `Data::from()` invokes the discriminator dispatch automatically.

## 1.0.0 - 2026-04-20

- Initial public release.
- Fluent client and `LaravelCloud` facade for the Laravel Cloud API.
- Coverage for applications, environments, deployments, databases, database snapshots, database clusters, caches, buckets, bucket keys, domains, instances, background processes, commands, websocket clusters and applications, dedicated clusters, organizations, and logs.
- JSON:API relationship hydration via `JsonApiHydrator`.
- Enum support with graceful fallback to raw strings for unknown API values.
